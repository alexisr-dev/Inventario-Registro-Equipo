<?php

namespace App\Filament\Resources\SolicitudResource\Pages;

use App\Filament\Resources\SolicitudResource;
use App\Mail\SolicitudApproved;
use App\Mail\SolicitudDecline;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class EditSolicitud extends EditRecord
{
    protected static string $resource = SolicitudResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Actualizar el registro con los nuevos datos
        $record->update($data);

        // Obtener el usuario que creó la solicitud (docente)
        $user = User::find($record->id_users);

        if ($user) {
            // Preparar los datos comunes para el correo
            $dataToSend = [
                'solicitud_id' => $record->id,
                'fecha_requerida' => $record->fecha_requerida,
                'hora_inicio' => $record->hora_inicio,
                'hora_fin' => $record->hora_fin,
                'aula' => $record->aula,
                'name' => $user->name,
                'email' => $user->email,
            ];

            // Enviar correo y notificación según el estado
            if ($record->estado === 'aprobada') {
                $dataToSend['estado'] = 'Aprobada'; // Estado de la solicitud
                Mail::to($user->email)->send(new SolicitudApproved($dataToSend));

                // Enviar notificación
                Notification::make()
                    ->title('Solicitud Aprobada')
                    ->success()
                    ->body("La solicitud para el día {$record->fecha_requerida} ha sido aprobada.")
                    ->sendToDatabase($user);
            } elseif ($record->estado === 'rechazada') {
                $dataToSend['estado'] = 'Rechazada'; // Estado de la solicitud
                Mail::to($user->email)->send(new SolicitudDecline($dataToSend));

                // Enviar notificación
                Notification::make()
                    ->title('Solicitud Rechazada')
                    ->danger()
                    ->body("La solicitud para el día {$record->fecha_requerida} ha sido rechazada.")
                    ->sendToDatabase($user);
            }
        }

        // Retornar el registro actualizado
        return $record;
    }
}
