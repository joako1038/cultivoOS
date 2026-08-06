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
        Schema::create('catalogo_variedads', function (Blueprint $table) {
             $table->uuid('id')->primary();
            $table->string('nombre',100);
            
             $table->string('banco',100);
             $table->text('descripcion')->nullable();
             $table->decimal('dias_ciclo', 8, 2);
            $table->timestamps();

            //relaciones
            // Relaciones
            $table->foreignUuid('tipo_variedad_id')
                ->constrained('tipo_variedads');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo_variedads');
    }
};
