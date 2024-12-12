<?php

use App\Filament\Pages\ExportInventarios;
use App\Http\Controllers\InventarioController;
use Illuminate\Support\Facades\Route;

// Redirección al panel de docente
Route::get('/', function () {
    return redirect('/docente');
});

Route::get('/inventarios/export', [InventarioController::class, 'exportAllInventories'])->name('inventarios.export');