<?php
namespace App\Filament\Docente\Resources\SolicitudResource\Pages;

use App\Filament\Docente\Resources\SolicitudResource;
use App\Mail\solicitudPending;
use App\Models\DetalleSolicitud;
use App\Models\Producto;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CreateSolicitud extends CreateRecord
{
    protected static string $resource = SolicitudResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Obtener el usuario autenticado y asignar los datos básicos
        $data['user_id'] = Auth::user()->id;
        $data['estado'] = 'pendiente';

        // Obtener los detalles de la solicitud y remover del array principal
        $detalles = $data['detalles_solicitud'] ?? [];
        unset($data['detalles_solicitud']);

        // Guardar los detalles en la sesión para acceder a ellos después
        session(['detalles_solicitud' => $detalles]);

        // Devolver los datos modificados al flujo de creación
        return $data;
    }

    protected function afterCreate(): void
    {
        $solicitud = $this->record;

        // Recuperar los detalles de la sesión
        $detalles = session('detalles_solicitud', []);

        // Crear los detalles de la solicitud y preparar la información para el correo
        $detallesInfo = [];
        foreach ($detalles as $detalle) {
            // Crear el detalle de la solicitud
            DetalleSolicitud::create([
                'solicitud_id' => $solicitud->id,
                'producto_id' => $detalle['producto_id'],
                'cantidad' => $detalle['cantidad'],
            ]);

            // Agregar información del producto y la cantidad al array
            $producto = Producto::find($detalle['producto_id']);
            $detallesInfo[] = [
                'producto' => $producto ? $producto->nombre : 'Producto no encontrado',
                'cantidad' => $detalle['cantidad'],
            ];
        }

        // Preparar los datos para el correo
        $dataToSend = [
            'fecha_requerida' => $solicitud->fecha_requerida,
            'hora_inicio' => $solicitud->hora_inicio,
            'hora_fin' => $solicitud->hora_fin,
            'aula' => $solicitud->aula,
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'detalles_solicitud' => $detallesInfo, // Agregar los detalles de la solicitud
        ];

        // Obtener el admin (o cualquier otro correo que deba recibir el correo)
        $userAdmin = User::find(1); // Asumiendo que el admin tiene ID 1

        // Enviar el correo
        Mail::to($userAdmin)->send(new solicitudPending($dataToSend));

        // Enviar notificación al usuario que creó la solicitud
       // Enviar notificación al usuario que creó la solicitud
$recipient = Auth::user(); // Usamos Auth::user() en lugar de auth()->user()

// Crear la notificación y enviarla
Notification::make()
    ->title('Notificación de Solicitud')
    ->info()
    ->body("la solicitud de equipos para el dia". $dataToSend['fecha_requerida'].  'esta pendiente por a aprobar')
    ->sendToDatabase($recipient);

    
        
        // Limpiar la sesión de los detalles
        session()->forget('detalles_solicitud');
    }
}
