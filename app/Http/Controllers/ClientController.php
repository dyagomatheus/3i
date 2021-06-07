<?php

namespace App\Http\Controllers;

use App\Mail\Devolution as MailDevolution;
use App\Mail\NewDevolution;
use App\Models\Client;
use App\Models\Devolution;
use App\Models\DevolutionStatus;
use App\Models\User;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:clients|max:255',
            'cnpj' => 'required|unique:clients',
            'phone' => 'nullable',
            'contact' => 'required',
            'password' => 'required',
            'email' => 'required|unique:users',
            'product_id' => 'required',
            'qty' => 'required',
            'value' => 'required',
            'number_nf' => 'required',
            'date_nf' => 'required',
            'defect' => 'required'
        ]);

        try {
            DB::beginTransaction();

            $client = Client::create([
                'name' => $request->name,
                'cnpj' => $request->cnpj,
                'phone' => $request->phone,
                'contact' => $request->contact
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'client_id' => $client->id,
                'password' => bcrypt($request->password),
                'type' => 'client'
            ]);

            for ($i=0; $i < count($request->product_id); $i++) {
                $devolution = Devolution::create([
                    'client_id' => $client->id,
                    'product_id' => $request->product_id[$i],
                    'qty' => $request->qty[$i],
                    'value' => $request->value[$i],
                    'number_nf' => $request->number_nf[$i],
                    'date_nf' => $request->date_nf[$i],
                    'defect' => $request->defect[$i],
                ]);

                $comment = 'Seu pedido foi enviado com sucesso, em breve retornaremos o contato.';
                DevolutionStatus::create([
                    'status' => 'Enviado',
                    'devolution_id' => $devolution->id,
                    'comment' => $comment
                ]);

                foreach ($devolution->client->users as $user) {
                    $mail = $user->email;
                }

                Mail::to($mail)->send(new MailDevolution('Enviado', $comment));
                Mail::to(env('MAIL_FROM_ADDRESS'))->send(new NewDevolution());
            }



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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
