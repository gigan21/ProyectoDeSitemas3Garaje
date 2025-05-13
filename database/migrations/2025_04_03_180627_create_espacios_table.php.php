<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('espacios', function (Blueprint $table) {
            $table->id();
            $table->string('numero_espacio', 10)->unique();
            $table->enum('estado', ['libre', 'ocupado', 'mantenimiento'])->default('libre');
            $table->foreignId('cliente_mensual_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('espacios');
    }
};