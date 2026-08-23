<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
                // 1. MIGRACIÓN: TIPO DE EQUIPAMIENTO (No ENUM, Lookup relacional extensible)
        Schema::create('tipo_equipamientos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('codigo')->unique(); // LUMINARIA, AIRE_ACONDICIONADO, CO2, etc.
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_equipamientos');
    }
};