<?php

namespace App\Filament\Docente\Resources;

use App\Filament\Docente\Resources\SolicitudResource\Pages;
use App\Filament\Docente\Resources\SolicitudResource\RelationManagers;
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
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Filters\DateFilter;






class SolicitudResource extends Resource
{
    protected static ?string $model = Solicitud::class;
    protected static ?string $pluralLabel = 'Solicitudes';
    protected static ?string $navigationLabel = 'Solicitudes';
    protected static ?string $slug = 'solicitudes';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function getEloquentQuery(): Builder
{
    $user = Auth::user();
    $roles = $user->roles->pluck('name'); // Obtener los nombres de los roles del usuario

    // Verifica si el usuario tiene el rol de administrador
    return parent::getEloquentQuery()
        ->with('usuario') // Incluye la relación usuario
        ->when(!$roles->contains('administrador'), function ($query) use ($user) {
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

                //          
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
                        ->maxLength(50),
                        Forms\Components\Textarea::make('comentario')
                        ->label('Comentario'),
                    Forms\Components\Repeater::make('detalles_solicitud')
                        ->relationship('detallesSolicitud')
                        ->schema([
                            Forms\Components\Select::make('producto_id')
                          ->label('Producto')
                        ->relationship('producto', 'nombre') // Nombre del campo que quieras mostrar
                         ->options(Producto::all()->pluck('nombre', 'id')) // Obtener los productos
                         ->required(),
                            Forms\Components\TextInput::make('cantidad')
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
                //

        Tables\Columns\TextColumn::make('usuario.name')
            ->label('Nombre de Usuario')
            ->sortable()
            ->searchable()
            ->hidden(fn () => !$roles->contains('administrador')),
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
            ->sortable(),
            Tables\Columns\TextColumn::make('detallesSolicitud.0.producto.nombre')
            ->label('Producto')
            ->sortable(),
        Tables\Columns\TextColumn::make('detallesSolicitud.0.cantidad')
            ->label('Cantidad')
            ->sortable(),
            ])
            ->filters([

                Tables\Filters\SelectFilter::make('estado')
                ->options([
                    'pendiente' => 'heroicon-o-clock text-yellow-500',
                    'aprobada' => 'heroicon-o-check-circle text-green-500',
                    'rechazada' => 'heroicon-o-x-circle text-red-500',
                ])
        
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
