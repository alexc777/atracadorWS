<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\GlobalsController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ordenesController;
use App\Http\Controllers\registerController;
use App\Http\Controllers\TableController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [LoginController::class, 'login']);

Route::get('/get/roles', [GlobalsController::class, 'getRoles']);

Route::post('/create/users', [registerController::class, 'createUsers']);
Route::post('/edit/users', [registerController::class, 'editUsers']);
Route::post('/delete/users', [registerController::class, 'deleteUsers']);
Route::get('/get/users', [registerController::class, 'getUsers']);

Route::post('/create/table', [TableController::class, 'createTable']);
Route::post('/edit/table', [TableController::class, 'editTable']);
Route::post('/delete/table', [TableController::class, 'deleteTable']);
Route::get('/get/table', [TableController::class, 'getTable']);


Route::post('/create/menu', [MenuController::class, 'createMenu']);
Route::post('/edit/menu', [MenuController::class, 'editMenu']);
Route::post('/delete/menu', [MenuController::class, 'deleteMenu']);
Route::get('/get/menu', [MenuController::class, 'getMenu']);


Route::get('/get/ordenes', [ordenesController::class, 'getOrdenes']);
Route::post('/create/ordenes', [ordenesController::class, 'createOrdenes']);
Route::post('/update/status/ordenes', [ordenesController::class, 'updateStatusOrder']);




