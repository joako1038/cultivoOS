<?php

namespace Database\Seeders;

use App\Models\TipoEquipamiento;
use Illuminate\Database\Seeder;

class TipoEquipamientoSeeder extends Seeder
{
    public function run(): void
    {
        TipoEquipamiento::create([
            'nombre' => 'LUMINARIA',
            'descripcion' => 'Equipamiento destinado a proporcionar iluminación para el cultivo.',
        ]);

        TipoEquipamiento::create([
            'nombre' => 'AIRE_ACONDICIONADO',
            'descripcion' => 'Equipamiento destinado al control de temperatura y climatización de la sala.',
        ]);

        TipoEquipamiento::create([
            'nombre' => 'CO2',
            'descripcion' => 'Equipamiento destinado a la generación, suministro o control de dióxido de carbono.',
        ]);

        TipoEquipamiento::create([
            'nombre' => 'VENTILADOR',
            'descripcion' => 'Equipamiento destinado a la circulación y movimiento del aire dentro de la sala.',
        ]);

        TipoEquipamiento::create([
            'nombre' => 'HUMIDIFICADOR',
            'descripcion' => 'Equipamiento destinado a incrementar la humedad relativa de la sala.',
        ]);

        TipoEquipamiento::create([
            'nombre' => 'DESHUMIDIFICADOR',
            'descripcion' => 'Equipamiento destinado a reducir la humedad relativa de la sala.',
        ]);

        TipoEquipamiento::create([
            'nombre' => 'RIEGO_AUTOMATICO',
            'descripcion' => 'Equipamiento destinado al suministro automatizado de agua. Su modelado específico será desarrollado posteriormente.',
        ]);
    }
}