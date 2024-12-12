<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;
    protected $table = 'inventario';
    protected $fillable = [
        'nombre',
        'descripcion',
        'marca',
        'modelo',
        'numero_serie',
        'estado',
        'producto_id',
        'ubicacion',
    ];

    public function detallesPrestamo() { 
        return $this->hasMany(DetallePrestamo::class);
     }
     public function producto()
     {
         return $this->belongsTo(Producto::class, 'producto_id');
     }
     
}
