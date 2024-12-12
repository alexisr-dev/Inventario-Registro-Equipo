<?php

namespace App\Filament\Resources\InventarioResource\Pages;

use App\Filament\Resources\InventarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventarios extends ListRecords
{
    protected static string $resource = InventarioResource::class;

    // Definir acciones en el encabezado
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('Descargar PDF')
                ->label('Exportar PDF')
                ->icon('heroicon-o-document')
                ->url(route('inventarios.export'))
                ->openUrlInNewTab(),
        ];
    }

}

