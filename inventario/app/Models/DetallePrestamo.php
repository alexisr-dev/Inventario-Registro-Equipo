<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePrestamo extends Model
{
    use HasFactory;
    protected $table = 'detalles_prestamo';


    protected $fillable = ['prestamo_id', 'cantidad', 'inventario_id'];



    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class);
}
public function inventario()
{
    return $this->belongsTo(Inventario::class, 'inventario_id');
}
public function producto() { return $this->belongsTo(Producto::class); }
}


