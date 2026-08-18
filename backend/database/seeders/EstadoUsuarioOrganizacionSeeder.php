<?php

namespace Database\Seeders;

use App\Models\EstadoUsuarioOrganizacion;
use Illuminate\Database\Seeder;

class EstadoUsuarioOrganizacionSeeder extends Seeder
{
    public function run(): void
    {
        EstadoUsuarioOrganizacion::create([
            'nombre' => 'ACTIVO',
            'descripcion' => 'El usuario pertenece activamente a la organización.',
        ]);

        EstadoUsuarioOrganizacion::create([
            'nombre' => 'PENDIENTE',
            'descripcion' => 'El usuario fue invitado y todavía no aceptó la invitación.',
        ]);

        EstadoUsuarioOrganizacion::create([
            'nombre' => 'SUSPENDIDO',
            'descripcion' => 'El usuario permanece asociado a la organización, pero tiene el acceso suspendido.',
        ]);
    }
}