<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;

use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;

Route::resource('client', ClientController::class);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::get('/devolution/edit/{id}', [DashboardController::class, 'devolutionEdit'])->middleware(['auth'])->name('devolution.edit');
Route::post('/devolution/update/{id}', [DashboardController::class, 'devolutionUpdate'])->middleware(['auth'])->name('devolution.update');
Route::get('/devolution/show/{id}', [DashboardController::class, 'devolutionShow'])->middleware(['auth'])->name('devolution.show');
Route::get('import-models', function () {
    return view('import');
});
Route::post('import-models', function () {
    Excel::import(new ProductsImport, request()->file('products'));
});
require __DIR__.'/auth.php';
