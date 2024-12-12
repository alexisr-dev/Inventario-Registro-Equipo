<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SolicitudResource\Pages;
use App\Models\Producto;
use App\Models\Solicitud;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\ViewAction;

class SolicitudResource extends Resource
{
    protected static ?string $model = Solicitud::class;
    protected static ?string $pluralLabel = 'Solicitudes';
    protected static ?string $navigationLabel = 'Solicitudes';
    protected static ?string $slug = 'solicitudes';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();  
        $roles = $user->roles->pluck('name'); // Obtener los nombres de los roles del usuario

        // Verifica si el usuario tiene el rol de administrador
        return parent::getEloquentQuery()
            ->with(['usuario', 'detallesSolicitud.producto']) // Asegúrate de cargar la relación producto
            ->when(!$roles->contains('super_admin'), function ($query) use ($user) {
                return $query->where('id_users', $user->id);
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('id_users')
                    ->default(fn () => Auth::id()) // Asigna el ID del usuario autenticado automáticamente
                    ->required(),
                Forms\Components\DateTimePicker::make('fecha_solicitud')
                    ->required()
                    ->label('Fecha de Solicitud')
                    ->default(now()), // Rellena con la fecha y hora actual al cargar el formulario
                Forms\Components\DatePicker::make('fecha_requerida')
                    ->required()
                    ->label('Fecha Requerida'),
                Forms\Components\TimePicker::make('hora_inicio')
                    ->required()
                    ->label('Hora de Inicio'),
                Forms\Components\TimePicker::make('hora_fin')
                    ->required()
                    ->label('Hora de Fin'),
                Forms\Components\TextInput::make('aula')
                    ->label('Aula')
                    ->required()
                    ->maxLength(50),
                    Forms\Components\Select::make('estado')
                ->options([
                    'pendiente' => 'pendiente',
                    'aprobada' =>  'aprobada' ,
                    'rechazada' => 'rechazada' ,
                ]),
                
                Forms\Components\Textarea::make('comentario')
                    ->label('Comentario'),
                Repeater::make('detalles_solicitud')
                    ->relationship('detallesSolicitud')
                    ->schema([
                        Select::make('producto_id')
                            ->label('Producto')
                            ->relationship('producto', 'nombre') // Nombre del campo que quieras mostrar
                            ->options(Producto::select('id', 'nombre')->pluck('nombre', 'id')) // Obtener los productos
                            ->required(),
                        TextInput::make('cantidad')
                            ->required()
                            ->label('Cantidad'),
                    ])
                    ->label('Detalles de Solicitud')
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('name');
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('usuario.name')
                    ->label('Nombre de Usuario')
                    ->sortable()
                    ->searchable()
                    ->hidden(fn () => !$roles->contains('super_admin')),
                Tables\Columns\TextColumn::make('fecha_solicitud')
                    ->label('Fecha de Solicitud')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_requerida')
                    ->label('Fecha Requerida')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hora_inicio')
                    ->label('Hora de Inicio')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hora_fin')
                    ->label('Hora de Fin')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('aula')
                    ->label('Aula'),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                    'aprobada' => 'success',
                  'pendiente' =>  'info',
                  'rechazada' => 'danger',
                  default => 'gray',
                       })
                    ->sortable(),
               /* Tables\Columns\TextColumn::make('detallesSolicitud.producto.nombre')
                    ->label('Producto')
                    ->sortable(),
                Tables\Columns\TextColumn::make('detallesSolicitud.cantidad')
                    ->label('Cantidad')
                    ->sortable(), */
            ])
            ->filters([

                Tables\Filters\SelectFilter::make('estado')
                ->options([
                   'pendiente' => 'pendiente',
                    'aprobada' =>  'aprobada' ,
                    'rechazada' => 'rechazada' ,
                    'cancelada' => 'cancelada',
                ])
                ])
            ->actions([
                Tables\Actions\EditAction::make(),
                ViewAction::make()
                
                    ->label('ver Detalle de Solicitud')
                    ->form(function ($record) {
                        return [
                            Repeater::make('detalles_solicitud')
                            ->relationship('detallesSolicitud')
                            ->schema([
                            Select::make('producto_id')
                                    ->label('Producto')
                                    ->relationship('producto', 'nombre') // Nombre del campo que quieras mostrar
                                    ->options(Producto::select('id', 'nombre')->pluck('nombre', 'id')) // Obtener los productos
                                    ->disabled(),
                                TextInput::make('cantidad')
                                    ->disabled()
                                    ->label('Cantidad'),
                                    ])   
                        ];
                    }),
                    Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Aquí puedes agregar relaciones si es necesario
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSolicituds::route('/'),
            'create' => Pages\CreateSolicitud::route('/create'),
            'edit' => Pages\EditSolicitud::route('/{record}/edit'),
        ];
    }
}
