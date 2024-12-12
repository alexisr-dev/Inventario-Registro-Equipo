<?php

namespace App\Filament\Widgets;

use App\Models\Inventario;
use App\Models\Solicitud;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminWidgetStats extends BaseWidget
{
    protected function getStats(): array
    {
        $totalPendientes = Solicitud::where('estado', 'pendiente')->count();
        $totalAprobadas = Solicitud::where('estado', 'aprobada')->count();
        $totalRechazadas = Solicitud::where('estado', 'rechazada')->count();
        $totalInventario = Inventario::count(); // Contador total de inventario
        return [
            //
            Stat::make('Solicitudes Pendientes', $totalPendientes)
            ->description('Solicitudes en proceso')
            ->color('info'),
        Stat::make('Solicitudes Aprobadas', $totalAprobadas)
            ->description('Solicitudes aprobadas')
            ->color('success'),
        Stat::make('Solicitudes Rechazadas', $totalRechazadas)
            ->description('Solicitudes no aceptadas')
            ->color('danger'),
    Stat::make('Total en Inventario', $totalInventario)
                ->description('Cantidad total de productos')
                ->color('primary'),

        ];
    }
}
