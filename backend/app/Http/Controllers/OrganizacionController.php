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
    /**
     * Muestra la vista principal de organizaciones para el usuario autenticado.
     */
    public function create(Request $request)
    {
        $usuario = Auth::user();

        // Cargar organizaciones asociadas al usuario con sus roles y salas
        $organizaciones = Organizacion::with(['salas', 'usuariosOrganizaciones.rol', 'usuariosOrganizaciones.usuario'])
            ->whereHas('usuariosOrganizaciones', function ($q) use ($usuario) {
                $q->where('usuario_id', $usuario->id);
            })
            ->orWhere('id', session('organizacion_activa_id'))
            ->get();

        $roles = Rol::all();
        $organizacionActivaId = session('organizacion_activa_id', $organizaciones->first()?->id);

        return Inertia::render('Organizaciones/Index', [
            'organizaciones'       => $organizaciones,
            'organizacionActivaId' => $organizacionActivaId,
            'roles'                => $roles,
            'usuarioActual'        => $usuario,
        ]);
    }

    /**
     * Guarda una nueva organización y asocia automáticamente al usuario con su rol.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'       => 'required|string|min:3|max:255',
            'descripcion'  => 'nullable|string|max:1000',
            'localizacion' => 'required|string|min:3|max:255',
            'rol_id'       => 'required|exists:rols,id',
        ]);

        $usuario = Auth::user();

        DB::transaction(function () use ($validated, $usuario, &$organizacion) {
            // 1. Crear Organización
            $organizacion = Organizacion::create([
                'nombre'       => $validated['nombre'],
                'descripcion'  => $validated['descripcion'] ?? '',
                'localizacion' => $validated['localizacion'],
            ]);

            // 2. Asociar al usuario creador con el rol en la pivot
            UsuarioOrganizacion::create([
                'usuario_id'      => $usuario->id,
                'organizacion_id' => $organizacion->id,
                'rol_id'          => $validated['rol_id'],
                'es_activo'       => true,
            ]);

            // 3. Establecer como activa en sesión
            session(['organizacion_activa_id' => $organizacion->id]);
        });

        return redirect()->back()->with('success', 'Organización creada y activada exitosamente.');
    }

    /**
     * Conmuta la organización activa en la sesión del usuario.
     */
    public function switchActiva(Request $request)
    {
        $request->validate([
            'organizacion_id' => 'required|exists:organizacions,id',
        ]);

        session(['organizacion_activa_id' => $request->organizacion_id]);

        return redirect()->back()->with('success', 'Entorno de organización cambiado.');
    }

}
