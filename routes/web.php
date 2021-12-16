<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DefectController;
use App\Http\Controllers\DevolutionController;
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

Route::prefix('group')->group(function () {
    Route::get('/edit/{id}', [DashboardController::class, 'groupEdit'])->middleware(['auth'])->name('group.edit');
    Route::post('/update/{id}', [DashboardController::class, 'groupUpdate'])->middleware(['auth'])->name('group.update');
    Route::get('/create', [DashboardController::class, 'groupCreate'])->middleware(['auth'])->name('group.create');
    Route::post('/store', [DashboardController::class, 'groupStore'])->middleware(['auth'])->name('group.store');
    Route::get('/show/{id}', [DashboardController::class, 'groupShow'])->middleware(['auth'])->name('group.show');
    Route::post('/search', [DashboardController::class, 'groupSearch'])->middleware(['auth'])->name('group.search');
    Route::get('/export-excel', [DashboardController::class, 'exportExcel'])->middleware(['auth'])->name('group.export.excel');
});

Route::get('/devolution/edit/{id}', [DevolutionController::class, 'devolutionEdit'])->middleware(['auth'])->name('devolution.edit');
Route::post('/devolution/update/{id}', [DevolutionController::class, 'devolutionUpdate'])->middleware(['auth'])->name('devolution.update');
Route::get('/devolution/create', [DevolutionController::class, 'devolutionCreate'])->middleware(['auth'])->name('devolution.create');
Route::post('/devolution/store', [DevolutionController::class, 'devolutionStore'])->middleware(['auth'])->name('devolution.store');
Route::get('/devolution/show/{id}', [DevolutionController::class, 'devolutionShow'])->middleware(['auth'])->name('devolution.show');
Route::post('/devolution/search', [DevolutionController::class, 'devolutionSearch'])->middleware(['auth'])->name('devolution.search');
Route::get('/devolution/export-excel', [DevolutionController::class, 'exportExcel'])->middleware(['auth'])->name('devolution.export.excel');

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
