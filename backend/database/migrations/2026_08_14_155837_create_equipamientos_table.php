<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipamientos', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('nombre', 100);
            $table->string('marca', 100)->nullable();
            $table->string('modelo', 100)->nullable();
            $table->string('numero_serie', 100)->nullable();

            $table->text('descripcion')->nullable();

            $table->foreignUuid('sala_id')
                ->constrained('salas');

            $table->foreignUuid('tipo_equipamiento_id')
                ->constrained('tipo_equipamientos');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipamientos');
    }
};