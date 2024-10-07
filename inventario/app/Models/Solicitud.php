<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';
    public function usuario()
    {
        return $this->belongsTo(User::class);
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
