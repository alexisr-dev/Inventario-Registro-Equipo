<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePrestamo extends Model
{
    use HasFactory;
   
    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class);
}
}
