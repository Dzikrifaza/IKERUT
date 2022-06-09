<?php

use App\Http\Controllers\ProdukApiController;
use App\Http\Controllers\ImageController;
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

Route::get('/image', [ImageController::class, 'getImage']);
Route::get('/produk', [ProdukApiController::class, 'index']);
Route::get('/product/create',);
