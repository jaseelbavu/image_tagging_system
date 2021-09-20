<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ImageController;
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

Route::post('register', [AuthController::class, 'register']);
Route::get('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('image')->group(function () {
        Route::post('upload', [ImageController::class, 'upload']);
    });
    Route::post('logout', [AuthController::class, 'logout']);
});