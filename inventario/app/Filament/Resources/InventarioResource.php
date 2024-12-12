<?php

namespace App\Filament\Resources;

use App\Filament\Pages\ExportInventarios;
use App\Filament\Resources\InventarioResource\Pages;
use App\Filament\Resources\InventarioResource\RelationManagers;
use App\Models\Inventario;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Collection;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class InventarioResource extends Resource
{
    protected static ?string $model = Inventario::class;
    protected static ?string $pluralLabel = 'Inventario';
    protected static ?string $navigationLabel = 'Inventario';
    protected static ?string $slug = 'inventario';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('producto_id')
                ->label('Producto')
                ->relationship('producto', 'nombre')
                ->required(),
                Forms\Components\TextInput::make('marca')
                    ->maxLength(50)
                    ->required(),
                Forms\Components\TextInput::make('modelo')
                    ->maxLength(50)
                    ->required(),
                Forms\Components\TextInput::make('numero_serie')
                    ->maxLength(50)
                    ->required(),
                    Forms\Components\TextInput::make('ubicacion')
                    ->maxLength(100)
                    ->required(),
            Forms\Components\Select::make('estado')
                ->label('Estado')
                ->options([
                    'disponible' => 'Disponible',
                    'en uso' => 'En uso',
                    'en mantenimiento' => 'En mantenimiento',
                    'dado de baja' => 'Dado de baja',
                ])
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
             
            ->columns([
                 Tables\Columns\TextColumn::make('producto.nombre')
                ->label('Producto')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('marca')
                ->label('Marca')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('modelo')
                ->label('Modelo')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('numero_serie')
                ->label('Número de Serie')
                ->sortable()
                ->searchable(),
                
                Tables\Columns\TextColumn::make('estado')
               ->label('Estado')
            ->badge()
          ->color(fn (string $state): string => match ($state) {
          'disponible' => 'success',
        'en uso' => 'warning',
        'en mantenimiento' =>  'info',
        'dado de baja' => 'danger',
        default => 'gray',
             })
                ])
            ->filters([
                //
            ])
            ->actions([
               

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()->exports([
                        ExcelExport::make('table')->fromTable()
                        ->withFilename('inventario_'.date('Y-m-d') . ' _export')
                        ->askForFilename()
                        ->askForWriterType()
                        ->withWriterType(\Maatwebsite\Excel\Excel::XLSX),
                        ExcelExport::make('form')->fromForm(),
                    ])
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
         
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventarios::route('/'),
            'create' => Pages\CreateInventario::route('/create'),
            'edit' => Pages\EditInventario::route('/{record}/edit'),
            
        ];
    }
}
