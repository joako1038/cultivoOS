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
        // 4. MIGRACIÓN: PLANIFICACION_TASKS (Tareas con evento_asociado_id nullable)
        Schema::create('planificacion_tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('planificacion_id')->constrained('planificacions')->cascadeOnDelete();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->integer('semana_sugerida')->default(1);
            $table->integer('dia_ciclo_sugerido')->nullable();
            $table->decimal('duracion_estimada_hs', 5, 2)->default(1.0);
            $table->string('tipo_accion'); // RIEGO, PODA, FERTIRRIEGO, FITOSANITARIO, etc.
            $table->boolean('completada')->default(false);
            $table->foreignUuid('evento_asociado_id')
                ->nullable()
                ->constrained('eventos')
                ->nullOnDelete();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planificacion_tasks');
    }
};
