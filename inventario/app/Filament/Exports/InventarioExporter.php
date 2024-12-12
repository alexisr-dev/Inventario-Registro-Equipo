<?php

namespace App\Filament\Exports;

use App\Models\Inventario;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Collection;
class InventarioExporter extends Exporter
{
    // Definir el modelo a exportar
    protected static ?string $model = Inventario::class;

    // Definir las columnas que se van a exportar
    public static function getColumns(): array
    {
        return [
            'ID' => 'id',               // Columna 'id' del modelo
            'Nombre' => 'nombre',       // Columna 'nombre' del modelo
            'Cantidad' => 'cantidad',   // Columna 'cantidad' del modelo
            'Precio' => 'precio',       // Columna 'precio' del modelo
            'Fecha de Creación' => 'created_at',  // Columna 'created_at' del modelo
            // Agrega aquí más columnas según las necesidades de tu modelo
        ];
    }

    // Mensaje de notificación cuando la exportación haya terminado
    public static function getCompletedNotificationBody(Export $export): string
    {
        // Mensaje personalizado de la notificación de finalización
        $body = 'Your inventario export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
