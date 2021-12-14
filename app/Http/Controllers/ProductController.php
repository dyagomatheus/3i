<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(5);

        return view('product.list', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('product.new');
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

        $product = new Product;
        $product->name = $request->name;
        $product->save();

        return redirect()
            ->back()
            ->with('success', 'Produto cadastrado com sucesso!');
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
        $product = Product::find($id);
        return view('product.edit', [
            'product' => $product
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

        $product = Product::find($id);

        $product->name = $request->name;
        $product->save();

        return redirect()
            ->back()
            ->with('success', 'Produto atualizado com sucesso!');
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

            Product::find($id)->delete();
            return redirect()
            ->back()
            ->with('success', 'Produto deletado com sucesso!');

        } catch (\Throwable $th) {
            Log::info("error: Deletar Produt: ". $th->getMessage());

            return redirect()
            ->back()
            ->with('error', 'Ocorreu um erro no seu pedido, por favor tente novamente!');
        }
    }

    public function search(Request $request)
    {
        $products = Product::orderBy('name');

        session()->put('product_name', $request->product_name);
        if($request->product_name) {
            $products = $products->where('name', 'like', '%'.$request->product_name.'%');
        }

        $products = $products->paginate(10000);

        return view('product.list', [
            'products' => $products
        ]);
    }
}
