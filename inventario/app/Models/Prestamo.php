<?php
namespace App\Models;

use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
    protected $with = ['detallesPrestamo'];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function detallesPrestamo()
    {
        return $this->hasMany(DetallePrestamo::class, 'prestamo_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
    public function inventario()
{
    return $this->belongsTo(Inventario::class, 'inventario_id');
}
public function prestamo() { 
    return $this->belongsTo(Prestamo::class);
}

}

