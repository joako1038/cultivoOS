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
        // 5. MIGRACIÓN: CONFIGURACIONES DE RIEGO POR CULTIVO (Campos configurables y sin defaults rígidos)
        Schema::create('configuraciones_riego_cultivo', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cultivo_id')->unique()->constrained('cultivos')->cascadeOnDelete();
            $table->string('estrategia')->default('VWC_HUMEDAD_SUSTRATO');
            $table->string('metodo_aplicacion')->default('GOTEO_SUPERFICIAL');
            $table->string('tipo_sustrato')->nullable(); // Configurable por el usuario
            $table->decimal('volumen_maceta_l', 5, 2)->nullable(); // Configurable por el usuario
            $table->string('tipo_maceta')->nullable(); // Configurable por el usuario
            $table->jsonb('fertilizantes_receta')->nullable(); // Array de [{ "fertilizante": "Base A", "ml_l": 2 }]
            $table->decimal('vwc_minimo_pct', 5, 2)->nullable();
            $table->decimal('vwc_objetivo_pct', 5, 2)->nullable();
            $table->decimal('vwc_maximo_pct', 5, 2)->nullable();
            $table->decimal('histeresis_pct', 5, 2)->nullable();
            $table->decimal('drenaje_objetivo_pct', 5, 2)->nullable();
            $table->decimal('ec_entrada_objetivo_ms', 5, 2)->nullable();
            $table->decimal('ec_drenaje_max_ms', 5, 2)->nullable();
            $table->decimal('ph_entrada_objetivo', 4, 2)->nullable();
            $table->integer('pulsos_diarios_sugeridos')->default(3);
            $table->integer('volumen_por_pulso_ml')->default(300);
            $table->decimal('volumen_max_diario_l', 8, 2)->nullable();
            $table->integer('tiempo_minimo_entre_pulsos_min')->default(90);
            $table->decimal('kc_cultivo', 4, 2)->nullable();
            $table->boolean('modo_automatico')->default(false);
            $table->boolean('modo_pausado')->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracion_riego_cultivos');
    }
};
