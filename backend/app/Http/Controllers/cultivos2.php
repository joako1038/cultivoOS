<?php

namespace App\Http\Controllers;

use App\Models\Cultivo;
use App\Models\CultivoVariedad;
use App\Models\CatalogoVariedad;
use App\Models\Planta;
use App\Models\Sala;
use App\Models\FaseCultivo;
use App\Models\EstadoCultivo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CultivoController extends Controller
{
    /**
     * Muestra el formulario para crear un nuevo cultivo
     */
    public function create()
    {
        return Inertia::render('Cultivos/Create', [
            'salas' => Sala::where('estado', 'ACTIVA')->get(['id', 'nombre', 'codigo', 'tipo', 'capacidad_macetas']),
            'fases' => FaseCultivo::orderBy('id')->get(['id', 'nombre', 'codigo', 'fotoperiodo_sugerido']),
            'estados' => EstadoCultivo::all(['id', 'nombre', 'color']),
            'usuarios' => User::all(['id', 'name', 'email']),
            'catalogoVariedades' => CatalogoVariedad::with('tipoVariedad:id,nombre,categoria')
                ->get(['id', 'nombre', 'banco', 'dias_ciclo', 'tipo_variedad_id']),
        ]);
    }

    /**
     * Almacena el Cultivo, sus CultivoVariedades y genera las Plantas en una transacción ACID
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'duracion_estimada' => 'required|numeric|min:1',
            'sala_id' => 'required|uuid|exists:salas,id',
            'fase_id' => 'required|uuid|exists:fases_cultivo,id',
            'estado_cultivo_id' => 'required|uuid|exists:estados_cultivo,id',
            'usuario_responsable_id' => 'required|uuid|exists:users,id',
            'generar_plantas_automaticas' => 'boolean',
            'variedades' => 'required|array|min:1',
            'variedades.*.catalogo_variedad_id' => 'required|uuid|exists:catalogo_variedads,id',
            'variedades.*.cantidad_plantas' => 'required|numeric|min:1',
            'variedades.*.fecha_inicio' => 'required|date',
            'variedades.*.descripcion' => 'nullable|string',
            'variedades.*.prefijo_codigo' => 'nullable|string|max:10',
        ]);

        $cultivo = DB::transaction(function () use ($validated, $request) {
            // 1. Crear el Cultivo Principal (App\Models\Cultivo)
            $cultivo = Cultivo::create([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'] ?? null,
                'duracion_estimada' => $validated['duracion_estimada'],
                'sala_id' => $validated['sala_id'],
                'fase_id' => $validated['fase_id'],
                'estado_cultivo_id' => $validated['estado_cultivo_id'],
                'usuario_responsable_id' => $validated['usuario_responsable_id'],
            ]);

            // Actualizar la sala si corresponde
            Sala::where('id', $validated['sala_id'])->update([
                'cultivo_activo_id' => $cultivo->id,
            ]);

            $plantaGlobalIndex = 1;

            // 2. Crear las Variedades Asignadas (App\Models\CultivoVariedad)
            foreach ($validated['variedades'] as $vData) {
                $cultivoVariedad = CultivoVariedad::create([
                    'descripcion' => $vData['descripcion'] ?? null,
                    'cantidad_plantas' => $vData['cantidad_plantas'],
                    'fecha_inicio' => $vData['fecha_inicio'],
                    'catalogo_variedad_id' => $vData['catalogo_variedad_id'],
                    // Si tu tabla cultivo_variedads tiene cultivo_id:
                    // 'cultivo_id' => $cultivo->id,
                ]);

                // 3. Generar las Plantas Individuales (App\Models\Planta)
                if ($request->input('generar_plantas_automaticas', true)) {
                    $prefijo = strtoupper($vData['prefijo_codigo'] ?? 'PLT');
                    $cantidad = (int)$vData['cantidad_plantas'];

                    for ($i = 1; $i <= $cantidad; $i++) {
                        Planta::create([
                            'codigo_planta' => sprintf('PLT-%s-%03d', $prefijo, $i),
                            'cultivo_variedad_id' => $cultivoVariedad->id,
                            'cultivo_id' => $cultivo->id,
                            'numero_maceta' => $plantaGlobalIndex,
                            'salud' => 'OPTIMO',
                            'estado' => 'EN_DESARROLLO',
                            'posicion' => sprintf('Fila %d - Pos %d', ceil($plantaGlobalIndex / 6), (($plantaGlobalIndex - 1) % 6) + 1),
                            'fecha_alta' => $vData['fecha_inicio'],
                        ]);
                        $plantaGlobalIndex++;
                    }
                }
            }

            return $cultivo;
        });

        return redirect()->route('cultivos.show', $cultivo->id)
            ->with('success', 'Cultivo y plantas creados exitosamente.');
    }

    /**
     * Muestra el detalle del cultivo con sus relaciones Eloquent cargadas
     */
    public function show(string $id)
    {
        $cultivo = Cultivo::with([
            'sala',
            'fase',
            'estadoCultivo',
            'usuarioResponsable',
            'registros' => fn($q) => $q->latest()->limit(10),
            'eventos' => fn($q) => $q->latest()->limit(10),
            'timeLines',
        ])->findOrFail($id);

        return Inertia::render('Cultivos/Show', [
            'cultivo' => $cultivo,
        ]);
    }
}
