<?php

namespace App\Http\Controllers;

use App\Mail\Devolution as MailDevolution;
use App\Mail\NewDevolution;
use App\Models\Devolution;
use App\Models\DevolutionStatus;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->type == 'admin') {
            $devolutions = Devolution::paginate(5);

        }else{
            $devolutions = Devolution::where('client_id', auth()->user()->client_id)->paginate(5);
        }

        return view('dashboard', [
            'devolutions' => $devolutions
        ]);
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

            DevolutionStatus::create([
                'status' => $request->status,
                'devolution_id' => $id,
                'comment' => $request->comment
            ]);
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
            dd($th);
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


                $devolution = Devolution::create([
                    'client_id' => $user->client_id,
                    'product_id' => $request->product_id,
                    'qty' => $request->qty,
                    'value' => $request->value,
                    'number_nf' => $request->number_nf,
                    'date_nf' => $request->date_nf,
                    'defect' => $request->defect
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
}
