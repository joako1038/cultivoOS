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
        Schema::create('plantas', function (Blueprint $table) {
             $table->uuid('id')->primary();
            $table->string('nombre',100);
             $table->text('descripcion')->nullable();
            $table->timestamps();
            $table->timestamp('fecha_inicio');
$table->foreignUuid('estado_planta_id')
                ->constrained('estado_plantas');
        $table->foreignUuid('cultivo_variedad_id')
                ->constrained('cultivo_variedads');
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantas');
    }
};
