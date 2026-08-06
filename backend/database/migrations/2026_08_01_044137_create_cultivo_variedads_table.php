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
        Schema::create('cultivo_variedads', function (Blueprint $table) {
           $table->uuid('id')->primary();
            $table->decimal('cantidad_pantas',8,2);
            $table->timestamp('fecha_inicio');
            $table->timestamps();
            $table->foreignUuid('catalogo_variedad_id')
                ->constrained('catalogo_variedads');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cultivo_variedads');
    }
};
