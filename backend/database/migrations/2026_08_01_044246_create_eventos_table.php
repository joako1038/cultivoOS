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
        Schema::create('eventos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre',100);
            $table->timestamp('fecha_inicio_planificacion');
            $table->timestamp('fecha_fin_planificacion');
            $table->text('descripcion')->nullable();
            $table->timestamps();
            $table->foreignUuid('tipo_evento_id')
                ->constrained('tipo_eventos');
$table->foreignUuid('estado_evento_id')
                ->constrained('estado_eventos');

$table->foreignUuid('usuario_id')
                ->constrained('usuarios');
$table->foreignUuid('time_line_id')
                ->constrained('time_lines');
        
$table->foreignUuid('cultivo_id')
                ->constrained('cultivos');
                
$table->foreignUuid('planta_id')
                ->constrained('plantas');
                
$table->foreignUuid('planificacion_id')
                ->constrained('planificacions');
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
