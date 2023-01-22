<?php

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

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/', [App\Http\Controllers\HomeController::class, 'store'])->name('home.store');
    Route::delete('/{link_id}', [App\Http\Controllers\HomeController::class, 'delete'])->name('home.delete');

    Route::get('/{shorten}', [App\Http\Controllers\HomeController::class, 'show'])->name('home.show');
    Route::get('/{shorten}/qrCode', [App\Http\Controllers\HomeController::class, 'qrCode'])->name('home.qrcode');
});