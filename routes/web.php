<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [HomeController::class, 'index']);
Route::post('/url', [HomeController::class, 'store']);
Route::get('/url', [HomeController::class, 'updateAll'])->name('url.updateAll');
Route::get('/url/{url}', [HomeController::class, 'update'])->name('url.update');
Route::delete('/url/{id}', [HomeController::class, 'destroy'])->name('url.delete');
