<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DenunciaAdminController;

Route::middleware('auth')->name('admin.')->group(function () {

    Route::get('/denuncias', [DenunciaAdminController::class, 'index'])->name('denuncias.index');
    
    Route::get('/denuncias/{id}', [DenunciaAdminController::class, 'show'])->name('denuncias.show');
    
    Route::patch('/denuncias/{id}/aprovar', [DenunciaAdminController::class, 'aprovar'])->name('denuncias.aprovar');
    
    Route::patch('/denuncias/{id}/rejeitar', [DenunciaAdminController::class, 'rejeitar'])->name('denuncias.rejeitar');
    
    Route::get('/denuncias/estatisticas', [DenunciaAdminController::class, 'estatisticas'])->name('denuncias.estatisticas');
});
