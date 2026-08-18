<?php

namespace App\Http\Controllers;
use App\Models\Organizacion;
use App\Models\Rol;
use App\Models\EstadoUsuarioOrganizacion;
use App\Models\UsuarioOrganizacion;



use Illuminate\Http\Request;
use Inertia\Inertia;

class OrganizacionController extends Controller
{

    public function create()
{
    return Inertia::render('Organizaciones/Create');
}

    public function store(Request $request)
    {
              $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string'],
        ]);

       $organizacion = Organizacion::create($datos);

        $rolOwner = Rol::where('nombre', 'OWNER')->firstOrFail(); 

        $estadoActivo = EstadoUsuarioOrganizacion::where('nombre', 'ACTIVO')->firstOrFail();

        UsuarioOrganizacion::create([
        'usuario_id' => $request->user()->id,
        'organizacion_id' => $organizacion->id,
        'rol_id' => $rolOwner->id,
        'estado_usuario_organizacion_id' => $estadoActivo->id,
        'es_propietario' => true,
]);

        $cantidadOrganizaciones= UsuarioOrganizacion::where('usuario_id', $request->user()->id)->count();
       
        if ($cantidadOrganizaciones>1) {
            // redirije a seleccionar org

        }else{
            session([
    'organizacion_id' => $organizacion->id

    ]);
     return redirect()->route('dashboard');
        }
       
  
    }

}
