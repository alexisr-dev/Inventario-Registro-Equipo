<?php

namespace App\Filament\Docente\Resources;

use App\Filament\Docente\Resources\SolicitudResource\Pages;
use App\Filament\Docente\Resources\SolicitudResource\RelationManagers;
use App\Models\DetalleSolicitud;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use App\Models\Solicitud;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Producto;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Filters\DateFilter;
use Filament\Tables\Actions\ViewAction;

class SolicitudResource extends Resource implements HasShieldPermissions
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
            ->with('usuario') // Incluye la relación usuario
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
                Forms\Components\Hidden::make('fecha_solicitud')
                    ->default(now()) // Establece la fecha automáticamente
                    ->required(),
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
                Forms\Components\TextInput::make('estado')
                    ->label('Estado')
                    ->default('pendiente') // Establece el valor por defecto
                    ->hidden(true), // Oculta el campo
                Forms\Components\Textarea::make('comentario')
                    ->label('Comentario'),
                Forms\Components\Repeater::make('detalles_solicitud')
                    ->schema([
                        Forms\Components\Select::make('producto_id')
                            ->label('Producto')
                            ->options(Producto::all()->pluck('nombre', 'id'))
                            ->required(),
                        Forms\Components\TextInput::make('cantidad')
                            ->required()
                            ->label('Cantidad'),
                    ])
                    ->label('Detalles de Solicitud')
                    ->afterStateHydrated(function ($component, $state, $record) {
                        if ($record) {
                            $component->state(static::getDetallesSolicitud($record->id));
                        }
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('name');

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('usuario.name')
                    ->label('Nombre')
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
                /* Tables\Columns\TextColumn::make('detallesSolicitud.0.producto.nombre')
                    ->label('Producto')
                    ->sortable(),
                Tables\Columns\TextColumn::make('detallesSolicitud.0.cantidad')
                    ->label('Cantidad')
                    ->sortable(), */
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'pendiente' => 'pendiente',
                        'aprobada' => 'aprobada',
                        'rechazada' => 'rechazada',
                        'cancelada' => 'cancelada',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                ViewAction::make()
                    ->label('Ver Detalle')
                    ->form(function ($record) {
                        $detallesSolicitud = DetalleSolicitud::where('solicitud_id', $record->id)->get();

                        return [
                            Repeater::make('detalles_solicitud')
                                ->schema([
                                    Select::make('producto_id')
                                        ->label('Producto')
                                        ->options(Producto::all()->pluck('nombre', 'id'))
                                        ->disabled(),
                                    TextInput::make('cantidad')
                                        ->disabled()
                                        ->label('Cantidad'),
                                ])
                                ->afterStateHydrated(function ($component, $state) use ($detallesSolicitud) {
                                    $state = $detallesSolicitud->map(function ($detalle) {
                                        return [
                                            'producto_id' => $detalle->producto_id,
                                            'cantidad' => $detalle->cantidad,
                                        ];
                                    })->toArray();
                                    $component->state($state);
                                })
                        ];
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSolicituds::route('/'),
            'create' => Pages\CreateSolicitud::route('/create'),
            'edit' => Pages\EditSolicitud::route('/{record}/edit'),
        ];
    }

    private static function getDetallesSolicitud($solicitudId)
    {
        if ($solicitudId) {
            return DetalleSolicitud::where('solicitud_id', $solicitudId)->get()->map(function ($detalle) {
                return [
                    'producto_id' => $detalle->producto_id,
                    'cantidad' => $detalle->cantidad,
                ];
            })->toArray();
        }
        return [];
    }
}
