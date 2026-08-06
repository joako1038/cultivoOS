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
        Schema::create('cultivos', function (Blueprint $table) {
           $table->uuid('id')->primary();
            $table->string('nombre',100);
             $table->text('descripcion')->nullable();
            $table->timestamps();
            $table->decimal('duracion_estimada',8,2);
            $table->foreignUuid('sala_id')
                ->constrained('salas');
            $table->foreignUuid('fase_id')
                ->constrained('fase_cultivos');
            $table->foreignUuid('estado_cultivo_id')
                ->constrained('estado_cultivos');
            $table->foreignUuid('usuario_responsable_id')
                ->constrained('usuarios');



            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cultivos');
    }
};
