<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // En el archivo generado (database/migrations/...)
public function up()
{
    Schema::table('espacios', function (Blueprint $table) {
        // Eliminar la FK antigua
        $table->dropForeign(['cliente_mensual_id']);
        
        // Renombrar la columna
        $table->renameColumn('cliente_mensual_id', 'cliente_id');
        
        // Nueva FK (acepta cualquier cliente)
        $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('espacios', function (Blueprint $table) {
        $table->dropForeign(['cliente_id']);
        $table->renameColumn('cliente_id', 'cliente_mensual_id');
        $table->foreign('cliente_mensual_id')->references('id')->on('clientes')->onDelete('set null');
    });
}
};
