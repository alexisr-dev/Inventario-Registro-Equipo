<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrestamoResource\Pages;
use App\Filament\Resources\PrestamoResource\RelationManagers;
use App\Models\Prestamo;
use App\Models\Solicitud;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PrestamoResource extends Resource
{
    protected static ?string $model = Prestamo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\Select::make('id_users')
    ->label('Usuario')
    ->options(User::all()->pluck('name', 'id'))
    ->required()
    ->reactive()
    ->afterStateUpdated(fn (callable $set) => $set('solicitud_id', null)), // Resetea solicitud_id al cambiar usuario

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
                ->nullable()
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('solicitud.id')->label('Solicitud ID'),
                Tables\Columns\TextColumn::make('user.name')
                ->label('Docente'),
                Tables\Columns\TextColumn::make('fecha_prestamo')->label('Fecha de Préstamo')->dateTime(),
                Tables\Columns\TextColumn::make('fecha_devolucion_estimada')->label('Fecha Devolución Estimada')->dateTime(),
                Tables\Columns\TextColumn::make('fecha_devolucion_real')
    ->label('Fecha Devolución Real')
    ->dateTime()
    ->getStateUsing(function ($record) {
        return $record->fecha_devolucion_real 
            ? \Carbon\Carbon::parse($record->fecha_devolucion_real)->format('Y-m-d H:i') 
            : 'N/A';
    }),

                Tables\Columns\TextColumn::make('estado')->label('Estado'),
            ])
            ->filters([
                //
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'en curso' => 'En Curso',
                        'devuelto' => 'Devuelto',
                        'atrasado' => 'Atrasado',
                    ])
                    ->label('Estado'),
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
            'index' => Pages\ListPrestamos::route('/'),
            'create' => Pages\CreatePrestamo::route('/create'),
            'edit' => Pages\EditPrestamo::route('/{record}/edit'),
        ];
    }
}
