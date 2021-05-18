<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;


Route::resource('client', ClientController::class);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::get('/devolution/edit/{id}', [DashboardController::class, 'devolutionEdit'])->middleware(['auth'])->name('devolution.edit');
Route::post('/devolution/update/{id}', [DashboardController::class, 'devolutionUpdate'])->middleware(['auth'])->name('devolution.update');
Route::get('/devolution/show/{id}', [DashboardController::class, 'devolutionShow'])->middleware(['auth'])->name('devolution.show');

require __DIR__.'/auth.php';
