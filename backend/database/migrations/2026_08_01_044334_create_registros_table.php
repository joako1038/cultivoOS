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
        Schema::create('registros', function (Blueprint $table) {
$table->uuid('id')->primary();
            $table->string('nombre',100);
            $table->timestamp('fecha_registro');
            $table->text('descripcion')->nullable();
            $table->timestamps();
            $table->decimal('vpd',8,2);
            $table->decimal('co2',8,2);
            $table->decimal('temperatura',8,2);
            $table->decimal('humedad',8,2);
            $table->decimal('temperatura_solucion',8,2);
            $table->decimal('temperatura_sustrato',8,2);
            $table->decimal('flujo_hora_intraccion',8,2);
            $table->decimal('flujo_hora_extracion',8,2);

            $table->foreignUuid('cultivo_id')
                ->constrained('cultivos');
$table->foreignUuid('usuario_id')
                ->constrained('usuarios');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registros');
    }
};
