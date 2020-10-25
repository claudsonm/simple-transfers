<?php

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

Route::post('/token', \App\Http\Controllers\AuthenticationController::class);

Route::post('/users', [\App\Http\Controllers\UserController::class, 'store']);
Route::middleware('auth:sanctum')->get('/users/{id}', [\App\Http\Controllers\UserController::class, 'show']);
