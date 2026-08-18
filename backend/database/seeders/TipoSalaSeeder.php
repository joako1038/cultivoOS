<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoSala;


class TipoSalaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoSala::create([
            'nombre' => 'Vegetativo',
            'descripcion' => 'Sala utilizada para cultivos en su periodo vegetativo',
        ]);

        TipoSala::create([
            'nombre' => 'Ciclo Completo',
            'descripcion' => 'Sala pensada para cultivos autoflorecientes o ciclos completos de fotoperiodicas',
        ]);

        TipoSala::create([
            'nombre' => 'Floracion  ',
            'descripcion' => 'Sala utilizada para cultivos en su periodo Floracion',
        ]);

        TipoSala::create([
            'nombre' => 'Madres',
            'descripcion' => 'Sala utilizada para mantener madres en vegetativo',
        ]);

        TipoSala::create([
            'nombre' => 'esquejes',
            'descripcion' => 'Sala utilizada para mantener esquejes en vegetativo',
        ]);

        TipoSala::create([
            'nombre' => 'esquejes y madres',
            'descripcion' => 'Sala utilizada para mantener esquejes y madres en vegetativo',
        ]);

        TipoSala::create([
            'nombre' => 'curado',
            'descripcion' => 'Sala utilizada para el fin del ciclo, secado y curado',
        ]);
    }
}
