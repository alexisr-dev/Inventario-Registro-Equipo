<?php

namespace App\Filament\Docente\Widgets;

use App\Models\Solicitud;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class DocenteWidgetStats extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();

        // Filtrar solicitudes por usuario autenticado
        $totalPendientes = Solicitud::where('estado', 'pendiente')
            ->where('id_users', $user->id)
            ->count();
        $totalAprobadas = Solicitud::where('estado', 'aprobada')
            ->where('id_users', $user->id)
            ->count();
        $totalRechazadas = Solicitud::where('estado', 'rechazada')
            ->where('id_users', $user->id)
            ->count();

        // Retornar estadísticas
        return [
            Stat::make('Solicitudes Pendientes', $totalPendientes)
                ->description('Solicitudes en proceso')
                ->color('info'),
            Stat::make('Solicitudes Aprobadas', $totalAprobadas)
                ->description('Solicitudes aprobadas')
                ->color('success'),
            Stat::make('Solicitudes Rechazadas', $totalRechazadas)
                ->description('Solicitudes no aceptadas')
                ->color('danger'),
        ];
    }
}
