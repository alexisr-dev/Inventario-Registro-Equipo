<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


class InventarioController extends Controller
{
    public function exportAllInventories()
    {
        $inventarios = Inventario::all(); // Fetch all inventories
        $pdf = Pdf::loadView('pdf.all_inventarios', compact('inventarios')); // Generate PDF
        return $pdf->download('todos_los_inventarios.pdf'); // Return PDF as a download
    }

}
