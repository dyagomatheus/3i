<?php

namespace App\Http\Controllers;

use App\Exports\DevolutionExport;
use App\Mail\Devolution as MailDevolution;
use App\Mail\NewDevolution;
use App\Models\Client;
use App\Models\Devolution;
use App\Models\Defect;
use App\Models\DevolutionStatus;
use App\Models\Product;
use App\Models\ProcessDevolution;
use App\Models\ProcessStatus;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->type == 'admin') {
            session()->forget('product_id');
            session()->forget('client_id');
            session()->forget('date');
            session()->forget('status');

            $clients = Client::get();
            $groups = ProcessDevolution::paginate(5);

            return view('dashboard', [
                'groups' => $groups,
                'clients' => $clients,
            ]);

        }else{
            $groups = Devolution::where('client_id', auth()->user()->client_id)->paginate(5);

            return view('dashboard', [
                'groups' => $groups
            ]);
        }
    }

    public function groupEdit($id)
    {
        if (auth()->user()->type != 'admin') {
            return redirect()->back();
        }

        $group = ProcessDevolution::find($id);

        return view('group.edit', [
            'group' => $group
        ]);

    }


    public function groupUpdate(Request $request, $id)
    {
        if (auth()->user()->type != 'admin') {
            return redirect()->back();
        }
        $group = ProcessDevolution::find($id);
        try {
            DB::beginTransaction();

            $status = ProcessDevolution::status($id);
            $today = new DateTime();

            $lastUpdate = new DateTime($status->created_at);
            $status->time = $lastUpdate->diff($today)->d .' Dias e ' . $lastUpdate->diff($today)->h . ' Horas';
            $status->save();

            ProcessStatus::create([
                'status' => $request->status,
                'group_id' => $id,
                'comment' => $request->comment,
            ]);

            $group->status = $request->status;
            $group->save();

            foreach ($group->client->users as $user) {
                $mail = $user->email;
            }


            Mail::to($mail)->send(new MailDevolution($request->status, $request->comment));
            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Pedido atualizado com sucesso!');

        } catch (\Throwable $th) {
            dd($th->getMessage());
            DB::rollback();
            Log::info("error: Atualiza????o Pedido". $th->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Ocorreu um erro na tualiza????o do pedido!');
        }

    }

    public function groupShow($id)
    {
        $user = Auth::user();

        if ($user->type != 'admin' ) {
            $group = ProcessDevolution::where('client_id', $user->client_id)->find($id);

            if(!$group)
                return redirect()->back();
        }else{
            $group = ProcessDevolution::find($id);
        }

        return view('group.details', [
            'group' => $group
        ]);
    }

    public function groupCreate()
    {
        $products = Product::all();
        $defects = Defect::all();
        return view('devolution.new', [
            'products' => $products,
            'defects' => $defects
        ]);
    }

    public function groupStore(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'product_id' => 'required',
            'value' => 'required',
            'number_nf' => 'required',
            'date_nf' => 'required',
            'defect' => 'required'
        ]);

        try {
            DB::beginTransaction();

            do {
                $number = rand(100000, 999999);
            } while (Devolution::where("number", "=", $number)->first() instanceof Devolution);

            $group = ProcessDevolution::create([
                'number' => $number,
                'client_id' =>  $user->client_id
            ]);

            ProcessStatus::create([
                'status' => 'Enviado',
                'group_id' => $group->id,
                'comment' => 'Seu pedido foi enviado com sucesso, em breve retornaremos o contato.'
            ]);

            for ($i=1; $i <= $request->qty; $i++) {
                $devolution = Devolution::create([
                    'client_id' => $user->client_id,
                    'product_id' => $request->product_id,
                    'value' => $request->value,
                    'number_nf' => $request->number_nf,
                    'date_nf' => $request->date_nf,
                    'defect' => $request->defect,
                    'status' => 'Enviado',
                    'group_id' => $group->id
                ]);

                $comment = 'Seu pedido foi enviado com sucesso, em breve retornaremos o contato.';
                DevolutionStatus::create([
                    'status' => 'Enviado',
                    'devolution_id' => $devolution->id,
                    'comment' => $comment
                ]);

                $mail = $user->email;

                Mail::to($mail)->send(new MailDevolution('Enviado', $comment));
                Mail::to(env('MAIL_FROM_ADDRESS'))->send(new NewDevolution());
            }
            DB::commit();

            return redirect()
                ->route('login');

        } catch (\Throwable $th) {
            dd($th);
            Log::info("error: Cadastro Cliente". $th->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Ocorreu um erro no seu pedido, por favor tente novamente!');
            DB::rollBack();
        }
    }

    public function groupSearch(Request $request)
    {
        if (auth()->user()->type == 'admin') {
            $clients = Client::get();
            $groups = ProcessDevolution::whereNotNull('client_id');

            // session()->put('product_id', $request->product_id);
            session()->put('client_id', $request->client_id);
            session()->put('date', $request->date);
            session()->put('status', $request->status);

            if($request->client_id) {
                $groups = $groups->where('client_id', $request->client_id);
            }

            if($request->date) {
                $groups = $groups->whereDate('created_at', $request->date);
            }

            if($request->status) {
                $groups = $groups->where('status', 'like', '%'.$request->status.'%');
            }

            $groups = $groups->paginate(10000);
            return view('dashboard', [
                'groups' => $groups,
                'clients' => $clients
            ]);

        }
    }


    public function exportExcel()
    {
        $devolutions = Devolution::whereNotNull('product_id');

            if(session()->get('client_id')) {
                $devolutions = $devolutions->where('client_id', session()->get('client_id'));
            }

            if(session()->get('date')) {
                $devolutions = $devolutions->whereDate('created_at', session()->get('date'));
            }

            if(session()->get('status')) {
                $devolutions = $devolutions->where('status', 'like', '%'.session()->get('status').'%');
            }

            $devolutions = $devolutions->get();

        $report = new DevolutionExport();

        $report->devolutions = $devolutions;
        return Excel::download($report, "RMA.xlsx");
    }
}
