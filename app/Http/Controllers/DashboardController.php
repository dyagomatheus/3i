<?php

namespace App\Http\Controllers;

use App\Mail\Devolution as MailDevolution;
use App\Models\Devolution;
use App\Models\DevolutionStatus;
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
}
