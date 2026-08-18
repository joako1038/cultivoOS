<?php

namespace Database\Seeders;

use App\Models\EstadoSala;
use Illuminate\Database\Seeder;

class EstadoSalaSeeder extends Seeder
{
    public function run(): void
    {
        EstadoSala::create([
            'nombre' => 'ACTIVA',
            'descripcion' => 'La sala se encuentra activa y disponible para su uso.',
        ]);

        EstadoSala::create([
            'nombre' => 'INACTIVA',
            'descripcion' => 'La sala existe pero actualmente no se encuentra en uso.',
        ]);

        EstadoSala::create([
            'nombre' => 'MANTENIMIENTO',
            'descripcion' => 'La sala se encuentra temporalmente fuera de servicio por tareas de mantenimiento.',
        ]);

        EstadoSala::create([
            'nombre' => 'PREPARACION',
            'descripcion' => 'La sala se encuentra en proceso de preparación para comenzar a utilizarse.',
        ]);
    }
}