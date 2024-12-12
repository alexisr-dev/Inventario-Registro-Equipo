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
        Schema::table('detalles_solicitud', function (Blueprint $table) {
            // Elimina la clave foránea actual (pero no elimina datos)
            $table->dropForeign(['solicitud_id']);

            // Agrega la clave foránea nuevamente con la opción onDelete('cascade')
            $table->foreign('solicitud_id')
                ->references('id')
                ->on('solicitudes')
                ->onDelete('cascade'); // Esto elimina automáticamente los detalles cuando se elimina una solicitud
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalles_solicitud', function (Blueprint $table) {
            // Revertir el cambio de la clave foránea
            $table->dropForeign(['solicitud_id']);
            $table->foreign('solicitud_id')
                ->references('id')
                ->on('solicitudes'); // Vuelve a las restricciones originales
        });
    }
};

