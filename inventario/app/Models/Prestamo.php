<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }
    public function detallesPrestamo()
    {
        return $this->hasMany(DetallePrestamo::class);
    } 
}
