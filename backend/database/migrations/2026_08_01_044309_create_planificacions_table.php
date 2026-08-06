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
        Schema::create('planificacions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre',100);
            $table->decimal('duracion_hs');
            $table->timestamp('fecha_inicio_planificacion');
            $table->timestamp('fecha_fin_planificacion');
            $table->text('descripcion')->nullable();
            $table->timestamps();
            $table->foreignUuid('tipo_planificacion_id')
                ->constrained('tipo_planificacions');
$table->foreignUuid('estado_planificacion_id')
                ->constrained('estado_planificacions');

$table->foreignUuid('time_line_id')
                ->constrained('time_lines');                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planificacions');
    }
};
