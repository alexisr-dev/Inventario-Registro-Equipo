<?php

namespace App\Models;
use App\Models\User; // Asegúrate de importar correctamente los modelos

use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
class Solicitud extends Model
{
     
    use HasFactory, HasRoles, HasPanelShield;
    protected $fillable = [
        'id_users',
        'fecha_solicitud',
        'fecha_requerida',
        'hora_inicio',
        'hora_fin',
        'aula',
        'estado',
        'comentario',
    ];
    protected $table = 'solicitudes';
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_users', 'id');
    }
public function detallesSolicitud()
    {
          return $this->hasMany(DetalleSolicitud::class);
    }
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }
    
}
