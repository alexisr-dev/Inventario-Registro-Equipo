<?php
namespace App\Filament\Resources\PrestamoResource\Pages;
use App\Filament\Resources\PrestamoResource;
use App\Models\Inventario;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms;
use App\Models\Producto;
use Filament\Actions\DeleteAction;
use App\Models\Prestamo;
use App\Models\Solicitud;
use App\Models\User;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class EditPrestamo extends EditRecord
{
    protected static string $resource = PrestamoResource::class;
    protected static ?string $model = Prestamo::class;
    /**
     * Sobrescribe el formulario para esta página.
     */
   
      function form(Forms\Form $form): Forms\Form
     {
         return $form->schema([
             Forms\Components\DateTimePicker::make('fecha_prestamo')
                 ->required()
                 ->label('Fecha de Préstamo'),
 
             Forms\Components\DateTimePicker::make('fecha_devolucion_estimada')
                 ->required()
                 ->label('Fecha de Devolución Estimada'),
 
             Forms\Components\DateTimePicker::make('fecha_devolucion_real')
                 ->label('Fecha de Devolución Real'),
 
             Forms\Components\Select::make('estado')
                 ->options([
                     'en curso' => 'En Curso',
                     'devuelto' => 'Devuelto',
                     'atrasado' => 'Atrasado',
                 ])
                 ->default('en curso')
                 ->required()
                 ->label('Estado')
                 ->afterStateUpdated(function (string $state, callable $set) {
                     // Actualiza los estados del inventario si el préstamo se devuelve
                     if ($state === 'devuelto') {
                         $this->devolverInventarios();
                     }
                 }),
 
                 Forms\Components\Repeater::make('detallesPrestamo')
                 ->relationship('detallesPrestamo') // Asegúrate de configurar esta relación en el modelo Prestamo
                 ->schema([
                     Forms\Components\Select::make('inventario_id')
                         ->label('Producto')
                         ->options(function ($get) {
                             // Verifica si el detalle ya tiene un valor de producto asignado
                             $inventarioId = $get('inventario_id');
                             
                             // Si no hay inventario_id asignado, mostramos solo los productos disponibles
                             if (!$inventarioId) {
                                 return Inventario::query()
                                     ->join('productos', 'inventario.producto_id', '=', 'productos.id')
                                     ->where('inventario.estado', 'disponible') // Filtra solo productos disponibles
                                     ->pluck('productos.nombre', 'inventario.id');
                                     
                             }
                             
                             // Si ya tiene un inventario_id asignado, no aplicamos el filtro
                             return Inventario::query()
                                 ->join('productos', 'inventario.producto_id', '=', 'productos.id')
                                 ->pluck('productos.nombre', 'inventario.id');
                                 
                         })
                         ->required()
                        
                         ->afterStateUpdated(function ($state) {
                             // Al seleccionar un producto, se marca como "En uso"
                             if ($state) {
                                 $this->actualizarEstadoInventario($state, 'En uso');
                             }
                         }),
             
                     Forms\Components\TextInput::make('cantidad')
                         ->label('Cantidad')
                         ->numeric()
                         ->required(),
                 ])
                 ->label('Detalles del Préstamo')
                 ->minItems(1)
                 ->required(),
             
         ]);
     }
 
     /**
      * Configuración de acciones en la página de edición.
      */
     protected function getActions(): array
     {
         return [
             DeleteAction::make()->after(function () {
                 // Si se elimina el préstamo, actualiza los inventarios asociados
                 $this->devolverInventarios();
             }),
         ];
     }
 
     /**
      * Devuelve todos los inventarios asociados al préstamo.
      */
     private function devolverInventarios()
     {
         $prestamo = $this->record;
 
         foreach ($prestamo->detallesPrestamo as $detalle) {
             $inventario = Inventario::find($detalle->inventario_id);
             if ($inventario) {
                 $inventario->update(['estado' => 'disponible']);
             }
         }
     }
 
     /**
      * Actualiza el estado de un inventario.
      */
     private function actualizarEstadoInventario(int $inventarioId, string $estado)
     {
         $inventario = Inventario::find($inventarioId);
         if ($inventario) {
             $inventario->update(['estado' => $estado]);
         }
     }
     protected function beforeSave(): void
    {
        $prestamo = $this->record;

        // Obtenemos los IDs actuales y nuevos de los inventarios en los detalles
        $originalDetalles = $prestamo->detallesPrestamo->pluck('inventario_id')->toArray();
        $nuevosDetalles = collect($this->data['detallesPrestamo'])->pluck('inventario_id')->toArray();

        // Identificamos los inventarios eliminados
        $inventariosEliminados = array_diff($originalDetalles, $nuevosDetalles);

        // Marcamos como "disponible" los inventarios eliminados
        if (!empty($inventariosEliminados)) {
            Inventario::whereIn('id', $inventariosEliminados)->update(['estado' => 'disponible']);
        }
    }

 }
