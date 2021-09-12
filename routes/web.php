<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DefectController;
use App\Http\Controllers\ProductController;
use App\Imports\ProductsImport;
use App\Models\Defect;
use Maatwebsite\Excel\Facades\Excel;

Route::resource('client', ClientController::class);
Route::any('client/search', [ClientController::class, 'search'])->name('client.search');

Route::get('/', function () {
    $defects = Defect::get();
    return view('welcome',[
        'defects' => $defects
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::get('/devolution/edit/{id}', [DashboardController::class, 'devolutionEdit'])->middleware(['auth'])->name('devolution.edit');
Route::post('/devolution/update/{id}', [DashboardController::class, 'devolutionUpdate'])->middleware(['auth'])->name('devolution.update');
Route::get('/devolution/create', [DashboardController::class, 'devolutionCreate'])->middleware(['auth'])->name('devolution.create');
Route::post('/devolution/store', [DashboardController::class, 'devolutionStore'])->middleware(['auth'])->name('devolution.store');
Route::get('/devolution/show/{id}', [DashboardController::class, 'devolutionShow'])->middleware(['auth'])->name('devolution.show');
Route::post('/devolution/search', [DashboardController::class, 'devolutionSearch'])->middleware(['auth'])->name('devolution.search');

Route::resource('product', ProductController::class);
Route::any('product/search', [ProductController::class, 'search'])->name('product.search');
Route::get('product/{id}/delete', [ProductController::class, 'delete'])->name('product.delete');

Route::resource('defect', DefectController::class);
Route::get('defect/{id}/delete', [DefectController::class, 'delete'])->name('defect.delete');

Route::get('import-models', function () {
    return view('import');
})->middleware(['auth']);

Route::post('import-models', function () {
    Excel::import(new ProductsImport, request()->file('products'));
})->middleware(['auth']);

require __DIR__.'/auth.php';
