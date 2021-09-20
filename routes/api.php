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

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::get('list', [ImageController::class, 'AllImages']);

Route::prefix('image')->as('image.')->group(function () {
    Route::get('{image_id}', [ImageController::class, 'viewImage'])->name('view');
    Route::get('{image_id}/tags', [ImageController::class, 'viewImageTags'])->name('tags');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('image')->as('image.')->group(function () {
        Route::post('upload', [ImageController::class, 'upload'])->name('upload');
        Route::post('{image_id}/tag', [ImageController::class, 'addImageTag'])->name('tag.add');
        Route::put('{image_id}/tag/{tag_id}/edit', [ImageController::class, 'editImageTag'])->name('tag.edit');
    });
    Route::get('myalbum', [ImageController::class, 'myAlbum']);
    Route::post('logout', [AuthController::class, 'logout']);
});