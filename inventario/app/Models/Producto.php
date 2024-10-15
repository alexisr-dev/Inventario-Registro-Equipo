<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'descripcion',
        'marca',
        'modelo',
        'numero_serie',
        'imagen',
        'categoria_id',
    ];
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
    public function inventarios()
    {
        return $this->hasMany(Inventario::class);
    }
}
