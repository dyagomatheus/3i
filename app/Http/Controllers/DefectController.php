<?php

namespace App\Http\Controllers;

use App\Models\Defect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DefectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $defects = Defect::paginate(5);

        return view('defect.list', [
            'defects' => $defects
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('defect.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->name)
            return redirect()
                ->back()
                ->with('error', 'O campo nome é obrigatório!');

        $defect = new Defect;
        $defect->name = $request->name;
        $defect->save();

        return redirect()
            ->back()
            ->with('success', 'Defeito cadastrado com sucesso!');
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
        $defect = Defect::find($id);

        return view('defect.edit', [
            'defect' => $defect
        ]);
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
        if(!$request->name)
            return redirect()
                ->back()
                ->with('error', 'O campo nome é obrigatório!');

        $defect = Defect::find($id);

        $defect->name = $request->name;
        $defect->save();

        return redirect()
            ->back()
            ->with('success', 'Defeito atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {

            Defect::find($id)->delete();
            return redirect()
            ->back()
            ->with('success', 'Defeito deletado com sucesso!');

        } catch (\Throwable $th) {
            Log::info("error: Deletar Produt: ". $th->getMessage());

            return redirect()
            ->back()
            ->with('error', 'Ocorreu um erro no seu pedido, por favor tente novamente!');
        }
    }

    public function search(Request $request)
    {
        $defects = Defect::orderBy('name');

        session()->put('defect_name', $request->defect_name);
        if($request->defect_name) {
            $defects = $defects->where('name', 'like', '%'.$request->defect_name.'%');
        }

        $defects = $defects->paginate(10000);

        return view('defect.list', [
            'defects' => $defects
        ]);
    }
}
