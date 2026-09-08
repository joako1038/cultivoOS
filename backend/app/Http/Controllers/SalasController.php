<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\TipoSala;
use App\Models\EstadoSala;
use App\Models\Equipamiento;
use App\Models\Organizacion;

use IlluminateHttpRequest;
use IlluminateSupportFacadesDB;
use InertiaInertia;
use InertiaResponse;

class SalasController extends Controller
{
    /**
     * Muestra el formulario para crear una nueva sala climatizada con equipamiento técnico.
     */
    public function create(Request $request): Response
    {
        $organizaciones = Organizacion::select('id', 'nombre')->get();
        $tiposSala = TipoSala::select('id', 'nombre', 'codigo')->get();
        $tiposEquipamiento = TipoEquipamiento::select('id', 'nombre', 'codigo')->get();

        return Inertia::render('Salas/Create', [
            'organizaciones' => $organizaciones,
            'tiposSala' => $tiposSala,
            'tiposEquipamiento' => $tiposEquipamiento,
            'organizacionActivaId' => $organizaciones->first()?->id,
        ]);
    }

    /**
     * Guarda la sala y registra transaccionalmente todos sus equipamientos asociados.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organizacion_id' => 'required|uuid|exists:organizacions,id',
            'nombre' => 'required|string|max:120',
            'codigo' => 'required|string|max:40|unique:salas,codigo',
            'tipo_sala_id' => 'nullable|uuid|exists:tipo_salas,id',
            'estado' => 'required|string|in:ACTIVA,MANTENIMIENTO,INACTIVA',
            'area_m2' => 'required|numeric|min:0.5',
            'altura_m' => 'required|numeric|min:0.5',
            'capacidad_macetas' => 'nullable|integer|min:0',
            'posee_co2' => 'boolean',
            'posee_extraccion' => 'boolean',
            'posee_intraccion' => 'boolean',
            'posee_ins_agua' => 'boolean',
            'descripcion' => 'nullable|string',
            'equipamientos' => 'nullable|array',
            'equipamientos.*.nombre' => 'required|string|max:120',
            'equipamientos.*.codigo_inventario' => 'nullable|string|max:60',
            'equipamientos.*.tipo' => 'required|string',
            'equipamientos.*.marca' => 'nullable|string|max:60',
            'equipamientos.*.modelo' => 'nullable|string|max:60',
            'equipamientos.*.potencia_w' => 'nullable|numeric|min:0',
            'equipamientos.*.especificaciones' => 'nullable|array',
        ]);

        DB::transaction(function () use ($validated) {
            // 1. Crear Sala
            $sala = Sala::create([
                'organizacion_id' => $validated['organizacion_id'],
                'nombre' => $validated['nombre'],
                'codigo' => $validated['codigo'],
                'tipo_sala_id' => $validated['tipo_sala_id'] ?? null,
                'area' => $validated['area_m2'],
                'altura' => $validated['altura_m'],
                'volumen' => round($validated['area_m2'] * $validated['altura_m'], 2),
                'posee_co2' => $validated['posee_co2'] ?? true,
                'posee_extraccion' => $validated['posee_extraccion'] ?? true,
                'posee_intraccion' => $validated['posee_intraccion'] ?? true,
                'posee_ins_agua' => $validated['posee_ins_agua'] ?? true,
                'descripcion' => $validated['descripcion'] ?? null,
            ]);

            // 2. Registrar Equipamientos vinculados
            if (!empty($validated['equipamientos'])) {
                foreach ($validated['equipamientos'] as $eqData) {
                    $tipoEq = TipoEquipamiento::where('codigo', $eqData['tipo'])->first();

                    Equipamiento::create([
                        'sala_id' => $sala->id,
                        'tipo_equipamiento_id' => $tipoEq?->id,
                        'nombre' => $eqData['nombre'],
                        'codigo_inventario' => $eqData['codigo_inventario'] ?? ('EQ-' . strtoupper(uniqid())),
                        'marca' => $eqData['marca'] ?? null,
                        'modelo' => $eqData['modelo'] ?? null,
                        'potencia_w' => $eqData['potencia_w'] ?? 0,
                        'especificaciones' => $eqData['especificaciones'] ?? [],
                        'estado' => 'OPERATIVO',
                        'calibrado' => true,
                    ]);
                }
            }
        });

        return redirect()->route('dashboard1.index')->with('success', 'Sala climatizada y equipamiento creados con éxito.');
    }
}
