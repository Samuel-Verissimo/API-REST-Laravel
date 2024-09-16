<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

Route::post('login', [AuthController::class, 'login']);
Route::get('/', [ProductController::class, 'apiDetails']);

Route::middleware('auth:api')->group(function () {

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{code}', [ProductController::class, 'show']);
    Route::put('/products/{code}', [ProductController::class, 'update']);
    Route::delete('/products/{code}', [ProductController::class, 'destroy']);

    Route::get('/food/files', [ProductController::class, 'filesOpenFood']);
    Route::post('/food/import/{file}', [ProductController::class, 'importFileOpenFood']);
    Route::post('/food/import/all/files', [ProductController::class, 'importAllFilesOpenFood']);

});