<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Resources\CategoriaResource;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { {
            // Captura a coluna para ordenacao
            $sortParameter = $request->input('ordenacao', 'nome_da_categoria');
            $sortDirection = Str::startsWith($sortParameter, '-') ? 'desc' : 'asc';
            $sortColumn = ltrim($sortParameter, '-');

            // Determina se faz a query ordenada ou aplica o default
            if ($sortColumn == 'nome_da_categoria') {
                $categorias = Categoria::orderBy('nomedacategoria', $sortDirection)->get();
            } else {
                $categorias = Categoria::all();
            }

            return response()->json([
                'status' => 200,
                'mensagem' => 'Lista de categorias retornada',
                "categorias" => CategoriaResource::collection($categorias)
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoriaRequest $request)
    {
        // Cria o objeto 
        $categoria = new Categoria();

        // Transfere os valores
        $categoria->nomedacategoria = $request->nome_da_categoria;

        // Salva
        $categoria->save();

        // Retorna o resultado
        return response()->json([
            'status' => 200,
            'mensagem' => 'Categoria criada',
            'categoria' => new CategoriaResource($categoria)
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show($categoriaid)
    {
        try{
            $validator = Validator::make(['id' => $categoriaid],
            [
                'id' => 'interger'
            ]);

            if($validator->fails()) {
                throw ValidationException::withMessages(['id' => 'O campo Id deve ser número']);
            }
        $categoria = Categoria::findorFail($categoriaid);

        return response()->json([
            'status' => 200,
            'mensagem' => 'Categoria retornada',
            'categoria' => new CategoriaResource($categoria)
            ]);
        }catch(\Exception $ex) {
            $class = get_class($ex);
            switch($class) {
                case ModelNotFoundException::class:
                    return response() -> json([
                        'status' => 404,
                        'mensagem' =>  'Categoria não encontrada',
                        'categoria' => []
                    ], 404);
                break;
                case ValidationException::class:
                    return response() -> json([
                        'status' => 406,
                        'mensagem' =>  $ex->getMessage(),
                        'categoria' => []
                    ], 406);
                break;
                default: 
                    return response() -> json([
                        'status' => 500,
                        'mensagem' =>  'Erro interno',
                        'categoria' => []
                    ], 500);
                    break;
            }
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCategoriaRequest $request, Categoria $categoria)
    {
        $categoria = Categoria::find($categoria->pkcategoria);
        $categoria->nomedacategoria = $request->nome_da_categoria;
        $categoria->update();

        return response()->json([
            'status' => 200,
            'mensagem' => 'Categoria atualizada'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();

        return response()->json([
            'status' => 200,
            'mensagem' => 'Categoria apagada'
        ], 200);
    }
}
