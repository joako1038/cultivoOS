<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        // 2. MIGRACIÓN: EQUIPAMIENTOS (Propiedad física de Sala)
        // REGLA: Un equipamiento pertenece físicamente a una SALA (sala_id).
        // NO posee directamente cultivo_id. La asignación/historial se realiza mediante cultivo_equipamientos.
        Schema::create('equipamientos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('sala_id')->constrained('salas')->cascadeOnDelete();
            $table->foreignUuid('tipo_equipamiento_id')->constrained('tipo_equipamientos');
            $table->string('nombre');
            $table->string('codigo_inventario')->unique();
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->jsonb('especificaciones')->nullable(); // Especificaciones técnicas flexibles
            $table->string('estado')->default('OPERATIVO'); // DISPONIBLE, OPERATIVO, EN_MANTENIMIENTO, INACTIVO
            $table->boolean('calibrado')->default(true);
            $table->date('fecha_ultima_calibracion')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('equipamientos');
    }
};