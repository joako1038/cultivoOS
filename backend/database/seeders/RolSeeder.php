<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        Rol::create([
            'nombre' => 'OWNER',
            'descripcion' => 'Propietario de la organización con acceso completo.',
        ]);

        Rol::create([
            'nombre' => 'ADMINISTRADOR',
            'descripcion' => 'Administra la organización y sus recursos.',
        ]);

        Rol::create([
            'nombre' => 'SUPERVISOR',
            'descripcion' => 'Supervisa la operación y el seguimiento de los cultivos.',
        ]);

        Rol::create([
            'nombre' => 'OPERADOR',
            'descripcion' => 'Realiza tareas operativas y registra actividades.',
        ]);

        Rol::create([
            'nombre' => 'LECTURA',
            'descripcion' => 'Puede consultar información sin modificarla.',
        ]);
    }
}
