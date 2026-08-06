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
        Schema::create('usuario_organizacions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->boolean('es_propietario')->default(false);
            $table->timestamps();
            $table->uuid('invitado_por');

            $table->foreignUuid('organizacion_id')
                ->constrained('organizacions');
            $table->foreignUuid('usuario_id')
                ->constrained('usuarios');
            $table->foreignUuid('rol_id')
                ->constrained('rols');
            $table->foreignUuid('estado_usuario_organizacion_id')
                ->constrained('estado_usuario_organizacions');
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_organizacions');
    }
};
