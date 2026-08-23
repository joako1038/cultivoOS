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
        // 6. MIGRACIÓN: EVENTOS DE RIEGO Y FERTIRRIEGO (EC y pH nullables)
        Schema::create('eventos_riego_fertirriego', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cultivo_id')->constrained('cultivos')->cascadeOnDelete();
            $table->dateTime('fecha_hora');
            $table->string('tipo_accion'); // RIEGO_AGUA, FERTIRRIGACION_NUTRIENTES, LAVADO_FLUSH, etc.
            $table->string('estrategia_utilizada')->nullable();
            $table->string('metodo_aplicacion')->nullable();
            $table->decimal('volumen_programado_l', 8, 2)->nullable();
            $table->decimal('volumen_aplicado_l', 8, 2)->nullable();
            $table->decimal('volumen_drenado_l', 8, 2)->nullable();
            $table->decimal('porcentaje_drenaje_calculado', 5, 2)->nullable();
            $table->decimal('ec_entrada_ms', 5, 2)->nullable(); // Nullable: riego sin medición EC
            $table->decimal('ec_drenaje_ms', 5, 2)->nullable();
            $table->decimal('ph_entrada', 4, 2)->nullable(); // Nullable: riego sin medición pH
            $table->decimal('ph_drenaje', 4, 2)->nullable();
            $table->decimal('vwc_antes_pct', 5, 2)->nullable();
            $table->decimal('vwc_despues_pct', 5, 2)->nullable();
            $table->decimal('vpd_kpa', 4, 2)->nullable();
            $table->decimal('temperatura_c', 4, 2)->nullable();
            $table->jsonb('fertilizantes_receta')->nullable();
            $table->string('estado')->default('COMPLETADO');
            $table->decimal('caudal_nominal_lph', 8, 2)->nullable();
            $table->decimal('caudal_real_lph', 8, 2)->nullable();
            $table->boolean('calibracion_valida')->default(true);
            $table->jsonb('alertas_detectadas')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_riego_fertiriegos');
    }
};
