<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\StoreProdutoRequest;
use App\Models\Produto;
use App\Http\Resources\ProdutoResource;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Carrega as tabelas adicionais
        $produto = Produto::with('categoria', 'marca')->get();

        return response() -> json([
            'status' => 200,
            'mensagem' => 'Lista de produtos retornada',
            'produtos' => ProdutoResource::collection($produto)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProdutoRequest $request)
    {
        // Cria o objeto 
        $produto =new Produto();

        // Transfere os valores
        $produto->nomedoproduto = $request->nome_do_produto;
        $produto->anodomodelo = $request->ano_do_modelo;
        $produto->precodelista = $request->preco_de_lista;
        //TODO: ha um jeito melhor de armazenar o ID?
        $produto->fkmarca = $request->marca['id'];
        $produto->fkcategoria = $request->categoria['id'];
        
        // Salva
        $produto->save();
        
        // Retorna o resultado
        return response() -> json([
            'status' => 200,
            'mensagem' => 'Produto armazenado',
            'produto' => new ProdutoResource($produto)
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function show(Produto $produto)
    {
        $produto = Produto::with('categoria', 'marca')->find($produto->pkproduto);

        return response() -> json([
            'status' => 200,
            'mensagem' => 'Produto retornado',
            'produto' => new ProdutoResource($produto)
        ], 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProdutoRequest $request, Produto $produto)
    {
        // Transfere os valores
        $produto->nomedoproduto = $request->nome_do_produto;
        $produto->anodomodelo = $request->ano_do_modelo;
        $produto->precodelista = $request->preco_de_lista;
        //TODO: ha um jeito melhor de armazenar o ID?
        $produto->fkmarca = $request->marca['id'];
        $produto->fkcategoria = $request->categoria['id'];
        
        // Salva
        $produto->update();
        
        // Retorna o resultado
        return response() -> json([
            'status' => 200,
            'mensagem' => 'Produto atualizado'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Produto $produto)
    {
        $produto->delete();
        return response() -> json([
            'status' => 200,
            'mensagem' => 'Produto apagado'
        ], 200);
    }
}
