<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventarioResource\Pages;
use App\Filament\Resources\InventarioResource\RelationManagers;
use App\Models\Inventario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
            Forms\Components\Select::make('estado')
                ->label('Estado')
                ->options([
                    'disponible' => 'Disponible',
                    'en uso' => 'En uso',
                    'en mantenimiento' => 'En mantenimiento',
                    'dado de baja' => 'Dado de baja',
                ])
                ->required(),
            Forms\Components\TextInput::make('ubicacion')
                ->label('Ubicación')
                ->nullable()
                ->maxLength(100)
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
        'en mantenimiento' => 'secondary',
        'dado de baja' => 'danger',
        default => 'gray',
             })
                ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
