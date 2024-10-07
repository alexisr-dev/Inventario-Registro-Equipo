<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes');
            $table->foreignId('id_users')->constrained('users');
            $table->dateTime('fecha_prestamo');
            $table->dateTime('fecha_devolucion_estimada');
            $table->dateTime('fecha_devolucion_real')->nullable();
            $table->enum('estado', ['en curso', 'devuelto', 'atrasado'])->default('en curso');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
