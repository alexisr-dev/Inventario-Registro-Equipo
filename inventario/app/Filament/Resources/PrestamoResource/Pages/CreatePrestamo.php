<?php

namespace App\Filament\Resources\PrestamoResource\Pages;

use App\Filament\Resources\PrestamoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class CreatePrestamo extends CreateRecord
{
    protected static string $resource = PrestamoResource::class;

    protected function afterCreate(): void
    {
        $prestamoId = $this->record->id; // Obtener el ID del préstamo recién creado

        try {
            // Iterar por los detalles de préstamo enviados desde el formulario
            foreach ($this->data['detallesPrestamo'] as $detalle) {
                // Validación opcional previa al llamado del procedimiento
                $cantidadDisponible = DB::table('inventario')
                    ->where('producto_id', $detalle['producto_id'])
                    ->where('estado', 'disponible')
                    ->count();

                if ($detalle['cantidad'] > $cantidadDisponible) {
                    throw new \Exception(
                        "Stock insuficiente para el producto con ID {$detalle['producto_id']}. Disponibles: {$cantidadDisponible}."
                    
                    );
                    
                }

                 // Llamada al procedimiento almacenado
                 DB::statement('CALL registrar_detalles_prestamo(?, ?, ?)', [
                    $prestamoId,              // ID del préstamo
                    $detalle['producto_id'],  // ID del producto
                    $detalle['cantidad'],     // Cantidad solicitada
                ]);
            }

            // Notificar éxito al usuario
            Notification::make()
                ->title('Préstamo registrado con éxito')
                ->success()
                ->send();
        } catch (\Exception $e) {
            // Manejar errores y notificar al usuario
            Notification::make()
                ->title('Error al registrar el préstamo')
                ->body($e->getMessage())
                ->danger()
                ->send();

            // Lanzar la excepción para detener el flujo
            throw $e;
        }
    }
}
