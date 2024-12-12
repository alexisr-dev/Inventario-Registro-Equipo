<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrestamoResource\Pages;
use App\Models\Prestamo;
use App\Models\Producto;
use App\Models\Solicitud;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class PrestamoResource extends Resource
{
    protected static ?string $model = Prestamo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Esquema del formulario principal del recurso.
     */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('id_users')
                ->label('Usuario')
                ->options(User::all()->pluck('name', 'id'))
                ->required()
                ->searchable()
                ->reactive()
                ->afterStateUpdated(fn (callable $set) => $set('solicitud_id', null)),

            Forms\Components\Select::make('solicitud_id')
                ->label('Buscar Solicitud')
                ->options(function (callable $get) {
                    $userId = $get('id_users');
                    return $userId
                        ? Solicitud::where('id_users', $userId)
                            ->join('users', 'solicitudes.id_users', '=', 'users.id')
                            ->select(DB::raw('CONCAT(users.name, " - ", solicitudes.fecha_solicitud) as solicitud_nombre'), 'solicitudes.id')
                            ->pluck('solicitud_nombre', 'solicitudes.id')
                        : [];
                })
                ->searchable()
                ->required(),

            Forms\Components\DateTimePicker::make('fecha_prestamo')
                ->required()
                ->label('Fecha de Préstamo'),

            Forms\Components\DateTimePicker::make('fecha_devolucion_estimada')
                ->required()
                ->label('Fecha de Devolución Estimada'),

            Forms\Components\DateTimePicker::make('fecha_devolucion_real')
            ->required()
                ->label('Fecha de Devolución Real'),

            Forms\Components\Select::make('estado')
                ->options([
                    'en curso' => 'En Curso',
                    'devuelto' => 'Devuelto',
                    'atrasado' => 'Atrasado',
                ])
                ->default('en curso')
                ->required()
                ->label('Estado'),
                Repeater::make('detallesPrestamo')
                ->schema([
                    Select::make('producto_id')
                        ->label('Producto')
                        ->options(Producto::all()->pluck('nombre', 'id'))
                        ->required(),
                    TextInput::make('cantidad')
                        ->label('Cantidad')
                        ->numeric()
                        ->required(),
                ])
                ->label('Detalles del Préstamo')
                ->minItems(1)
                ->required()
            
        ]);
    }

    /**
     * Configuración de la tabla del recurso.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('solicitud.id')->label('Solicitud ID'),
                Tables\Columns\TextColumn::make('user.name')->label('Docente'),
                Tables\Columns\TextColumn::make('fecha_prestamo')
                    ->label('Fecha de Préstamo')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('fecha_devolucion_estimada')
                    ->label('Fecha Devolución Estimada')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('fecha_devolucion_real')
                    ->label('Fecha Devolución Real')
                    ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('Y-m-d H:i') : 'N/A'),
                Tables\Columns\TextColumn::make('estado')->label('Estado'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /**
     * Relaciones del recurso.
     */
    public static function getRelations(): array
    {
        return [];
    }

    /**
     * Páginas del recurso.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrestamos::route('/'),
            'create' => Pages\CreatePrestamo::route('/create'),
            'edit' => Pages\EditPrestamo::route('/{record}/edit'),
        ];
    }
    
}
