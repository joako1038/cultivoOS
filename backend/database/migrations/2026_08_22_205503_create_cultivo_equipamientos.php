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
        // 3. MIGRACIÓN: CULTIVO_EQUIPAMIENTOS (Tabla Intermedia & Historial de Asignación)
        // Registra qué cultivo utilizó qué equipo y durante qué período (fecha_inicio -> fecha_fin).
        Schema::create('cultivo_equipamientos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cultivo_id')->constrained('cultivos')->cascadeOnDelete();
            $table->foreignUuid('equipamiento_id')->constrained('equipamientos')->cascadeOnDelete();
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin')->nullable(); // null indica asignación activa en el cultivo
            $table->text('notas')->nullable();
            $table->timestamps();

            // Índices para consultas de historial y equipos en uso
            $table->index(['cultivo_id', 'fecha_fin']);
            $table->index(['equipamiento_id', 'fecha_fin']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cultivo_equipamientos');
    }
};
