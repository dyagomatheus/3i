<?php

namespace App\Http\Controllers;

use App\Exports\DevolutionExport;
use App\Mail\Devolution as MailDevolution;
use App\Mail\NewDevolution;
use App\Models\Client;
use App\Models\Devolution;
use App\Models\DevolutionStatus;
use App\Models\Product;
use App\Models\User;
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

            $products = Product::get();
            $clients = Client::get();
            $devolutions = Devolution::paginate(5);

            return view('dashboard', [
                'devolutions' => $devolutions,
                'clients' => $clients,
                'products' => $products
            ]);

        }else{
            $devolutions = Devolution::where('client_id', auth()->user()->client_id)->paginate(5);

            return view('dashboard', [
                'devolutions' => $devolutions
            ]);
        }
    }

    public function devolutionEdit($id)
    {
        if (auth()->user()->type != 'admin') {
            return redirect()->back();
        }

        $devolution = Devolution::find($id);

        return view('devolution.edit', [
            'devolution' => $devolution
        ]);

    }


    public function devolutionUpdate(Request $request, $id)
    {
        if (auth()->user()->type != 'admin') {
            return redirect()->back();
        }
        $devolution = Devolution::find($id);
        try {
            DB::beginTransaction();

            $status = Devolution::status($id);
            $today = new DateTime();

            $lastUpdate = new DateTime($status->created_at);
            $status->time = $lastUpdate->diff($today)->d .' Dias e ' . $lastUpdate->diff($today)->h . ' Horas';
            $status->save();

            DevolutionStatus::create([
                'status' => $request->status,
                'devolution_id' => $id,
                'comment' => $request->comment,
            ]);

            $devolution->status = $request->status;
            $devolution->save();

            foreach ($devolution->client->users as $user) {
                $mail = $user->email;
            }


            Mail::to($mail)->send(new MailDevolution($request->status, $request->comment));
            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Pedido atualizado com sucesso!');

        } catch (\Throwable $th) {
            DB::rollback();
            Log::info("error: Atualização Pedido". $th->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Ocorreu um erro na tualização do pedido!');
        }

    }

    public function devolutionShow($id)
    {
        $user = Auth::user();

        if ($user->type != 'admin' ) {
            $devolution = Devolution::where('client_id', $user->client_id)->find($id);

            if($devolution)
                return redirect()->back();
        }else{
            $devolution = Devolution::find($id);
        }

        return view('devolution.details', [
            'devolution' => $devolution
        ]);
    }

    public function devolutionCreate()
    {
        $products = Product::all();
        return view('devolution.new', [
            'products' => $products
        ]);
    }

    public function devolutionStore(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'product_id' => 'required',
            'qty' => 'required',
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

                $devolution = Devolution::create([
                    'client_id' => $user->client_id,
                    'product_id' => $request->product_id,
                    'qty' => $request->qty,
                    'value' => $request->value,
                    'number_nf' => $request->number_nf,
                    'date_nf' => $request->date_nf,
                    'defect' => $request->defect,
                    'status' => 'Enviado',
                    'number' => $number
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

            DB::commit();

            return redirect()
                ->route('login');

        } catch (\Throwable $th) {
            Log::info("error: Cadastro Cliente". $th->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Ocorreu um erro no seu pedido, por favor tente novamente!');
            DB::rollBack();
        }
    }

    public function devolutionSearch(Request $request)
    {
        if (auth()->user()->type == 'admin') {
            $products = Product::get();
            $clients = Client::get();
            $devolutions = Devolution::whereNotNull('product_id');

            session()->put('product_id', $request->product_id);
            session()->put('client_id', $request->client_id);
            session()->put('date', $request->date);
            session()->put('status', $request->status);

            if($request->client_id) {
                $devolutions = $devolutions->where('client_id', $request->client_id);
            }

            if($request->product_id) {
                $devolutions = $devolutions->where('product_id', $request->product_id);
            }

            if($request->date) {
                $devolutions = $devolutions->whereDate('created_at', $request->date);
            }

            if($request->status) {
                $devolutions = $devolutions->where('status', 'like', '%'.$request->status.'%');
            }

            $devolutions = $devolutions->paginate(10000);
            return view('dashboard', [
                'devolutions' => $devolutions,
                'clients' => $clients,
                'products' => $products
            ]);

        }
    }


    public function exportExcel()
    {
        $devolutions = Devolution::whereNotNull('product_id');

            if(session()->get('client_id')) {
                $devolutions = $devolutions->where('client_id', session()->get('client_id'));
            }

            if(session()->get('product_id')) {
                $devolutions = $devolutions->where('product_id', session()->get('product_id'));
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
