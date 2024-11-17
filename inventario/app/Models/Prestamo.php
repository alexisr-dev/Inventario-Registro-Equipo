<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;
    protected $table = 'prestamos';
    protected $fillable = [
        'solicitud_id',
        'id_users',
        'fecha_prestamo',
        'fecha_devolucion_estimada',
        'fecha_devolucion_real',
        'estado',
    ];
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }
    public function detallesPrestamo()
    {
        return $this->hasMany(DetallePrestamo::class);
    } 
    public function user()
{
    return $this->belongsTo(User::class, 'id_users');
}
}
