<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index']);
Route::post('/url', [HomeController::class, 'store']);
Route::get('/url', [HomeController::class, 'updateAll'])->name('url.updateAll');
Route::get('/url/{url}', [HomeController::class, 'update'])->name('url.update');
Route::delete('/url/{id}', [HomeController::class, 'destroy'])->name('url.delete');
