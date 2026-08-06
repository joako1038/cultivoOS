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
        Schema::create('time_lines', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre',100);
            $table->timestamp('fecha_prevista_fin');
            $table->timestamp('fecha_real_fin');
            $table->timestamp('fecha_inicio');
            $table->timestamps();
            $table->foreignUuid('cultivo_id')
                ->constrained('cultivos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_lines');
    }
};
