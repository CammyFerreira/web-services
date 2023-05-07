<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\MarcaController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\PedidoController;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\ItemdopedidoController;
use App\Http\Controllers\Api\PassportAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);
Route::post('logout', [PassportAuthController::class, 'logout'])->middleware('auth:api');
Route::get('user', [PassportAuthController::class, 'userInfo'])->middleware('auth:api');

// Rota para categorias
route::apiResource('categorias', CategoriaController::class)->middleware('auth:api');

// Rota para marcas
route::apiResource('marcas', MarcaController::class);

// Rota para clientes
route::apiResource('clientes', ClienteController::class);

// Rota para os produtos
route::apiResource('produtos', ProdutoController::class);

// Rota para os produtos
route::apiResource('clientes.pedidos', PedidoController::class)->shallow();

// Rota para os produtos
route::apiResource('pedidos.itensdopedido', ItemdopedidoController::class);