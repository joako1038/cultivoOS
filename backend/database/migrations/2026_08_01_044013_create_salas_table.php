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
        Schema::create('salas', function (Blueprint $table) {
          // Primary Key
            $table->uuid('id')->primary();

            // Strings
            $table->string('nombre', 100);

            // Decimales
            $table->decimal('area', 8, 2);
            $table->decimal('altura', 5, 2);
            $table->decimal('volumen', 8, 2);

            // Booleanos
            $table->boolean('posee_intraccion')->default(false);
            $table->boolean('posee_extraccion')->default(false);
            $table->boolean('posee_co2')->default(false);
            $table->boolean('posee_ins_agua')->default(false);

            // Relaciones
            $table->foreignUuid('tipo_sala_id')
                ->constrained('tipo_salas');

            $table->foreignUuid('estado_sala_id')
                ->constrained('estado_salas');

            $table->foreignUuid('organizacion_id')
                ->constrained('organizacions');

            // Fechas
            $table->timestamps();

            // Soft Delete
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salas');
    }
};
