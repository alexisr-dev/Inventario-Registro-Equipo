<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleSolicitud extends Model
{
    protected $table = 'detalles_solicitud';
    use HasFactory;
    
    protected $fillable = [
        'id',
        'solicitud_id',
        'producto_id',
        'cantidad',
    ];
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
    

    
}
