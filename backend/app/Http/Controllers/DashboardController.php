<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use App\Models\Planificacion;
use App\Models\Evento;
use App\Models\TipoPlanificacion;
use App\Models\EstadoPlanificacion;
use App\Models\TipoEvento;
use App\Models\EstadoEvento;
use App\Models\TimeLine;
use App\Models\Sala;
use App\Models\Cultivo;
use App\Models\CatalogoVariedad;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;
class DashboardController extends Controller
{
    /**
     * Muestra el Dashboard principal de CultivoOS cargando:
     * - Salas y telemetría calculada
     * - Cultivos activos con jerarquía multivarietal (Cultivo -> CultivoVariedad -> CatalogoVariedad)
     * - Planificaciones activas y su TimeLine
     * - Eventos operativos
     * - Registros diarios de telemetría
     */
    public function index(): Response
    {
        // 1. Salas con equipamientos y telemetría mapeada
        $salas = Sala::with(['equipamientos.tipoEquipamiento'])
            ->orderBy('nombre')
            ->get()
            ->map(function ($sala) {
                // Cálculo de potencia de luces instaladas
                $potenciaLuces = 0;
                if ($sala->equipamientos && $sala->equipamientos->isNotEmpty()) {
                    $potenciaLuces = $sala->equipamientos->sum(function ($eq) {
                        return (float) ($eq->potencia_w ?? $eq->potencia ?? 0);
                    });
                }
                if ($potenciaLuces === 0 && isset($sala->potencia_luces_w)) {
                    $potenciaLuces = (float) $sala->potencia_luces_w;
                }
                if ($potenciaLuces === 0) {
                    $potenciaLuces = 1200; // Fallback estándar si aún no tiene luminarias asignadas
                }

                // Extracción de dimensiones
                $dimensionTexto = $sala->dimension ?? ($sala->ancho_m && $sala->largo_m ? "{$sala->ancho_m}m × {$sala->largo_m}m" : '6.00m × 4.00m');
                $ancho = (float) ($sala->ancho_m ?? 6.0);
                $largo = (float) ($sala->largo_m ?? 4.0);
                if ($sala->dimension && str_contains($sala->dimension, 'x')) {
                    $partes = explode('x', str_replace(['m', ' '], '', $sala->dimension));
                    if (count($partes) >= 2) {
                        $ancho = (float) $partes[0];
                        $largo = (float) $partes[1];
                    }
                }

                return [
                    'id' => $sala->id,
                    'codigo' => $sala->codigo ?? 'SAL-' . strtoupper(substr($sala->id, 0, 6)),
                    'nombre' => $sala->nombre,
                    'tipo' => $sala->tipo ?? $sala->tipo_sala ?? ($sala->tipo_sala_id ? 'FLORACION' : 'GENERAL'),
                    'dimension' => $dimensionTexto,
                    'ancho_m' => $ancho,
                    'largo_m' => $largo,
                    'altura' => (float) ($sala->altura ?? 3.5),
                    'capacidad_macetas' => (int) ($sala->capacidad_macetas ?? $sala->capacidad_plantas ?? 48),
                    'temperatura_c' => (float) ($sala->temperatura_c ?? $sala->temperatura_objetivo ?? 24.5),
                    'humedad_pct' => (float) ($sala->humedad_pct ?? $sala->humedad_objetivo ?? 50.0),
                    'vpd_kpa' => (float) ($sala->vpd_kpa ?? $sala->vpd_objetivo ?? 1.25),
                    'co2_ppm' => (int) ($sala->co2_ppm ?? $sala->co2_objetivo ?? 1100),
                    'potencia_luces_w' => $potenciaLuces,
                    'estado' => $sala->estado ?? 'ACTIVA',
                    'equipamientos' => $sala->equipamientos ?? [],
                ];
            });

        // 2. Cultivos activos con jerarquía de variedades y métricas fenológicas calculadas
        $cultivos = Cultivo::with([
            'cultivoVariedades.catalogoVariedad.tipoVariedad',
            'cultivoVariedades.plantas',
            'sala.equipamientos',
            'fase',
            'estadoCultivo',
        ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($cultivo) {
                // Variedades vinculadas (Cultivo -> CultivoVariedad -> CatalogoVariedad)
                $relVariedades = $cultivo->cultivoVariedades ?? $cultivo->cultivo_variedades ?? collect();
                
                // Cálculo de plantas totales
                $plantasTotales = $relVariedades->sum(function ($cv) {
                    return (int) ($cv->cantidad_plantas ?? $cv->plantas?->count() ?? 0);
                });
                if ($plantasTotales === 0 && isset($cultivo->plantas_totales)) {
                    $plantasTotales = (int) $cultivo->plantas_totales;
                }
                if ($plantasTotales === 0) {
                    $plantasTotales = 48; // Fallback
                }

                // Identificación de variedad principal y banco
                $primeraCv = $relVariedades->first();
                $catVar = $primeraCv?->catalogoVariedad;
                $variedadNombre = $catVar?->nombre ?? ($cultivo->variedad_nombre ?? 'Multivarietal');
                $banco = $catVar?->banco ?? ($cultivo->banco ?? "Barney's Farm");

                // Cálculo de días transcurridos y semana actual
                $fechaInicio = $cultivo->fecha_inicio ? Carbon::parse($cultivo->fecha_inicio) : now()->subDays(35);
                $diasTranscurridos = max(1, (int) $fechaInicio->diffInDays(now()));
                $semanaActual = $cultivo->semana_actual ?? max(1, (int) ceil($diasTranscurridos / 7));
                $duracionEstimada = (int) ($cultivo->duracion_estimada ?: ($catVar?->dias_ciclo ?: 63));
                if ($duracionEstimada <= 0) $duracionEstimada = 63;
                $porcentajeAvance = min(100, (int) round(($diasTranscurridos / $duracionEstimada) * 100));

                return [
                    'id' => $cultivo->id,
                    'codigo' => $cultivo->codigo ?? 'LOT-' . strtoupper(substr($cultivo->id, 0, 6)),
                    'nombre' => $cultivo->nombre ?? "Lote {$cultivo->codigo}",
                    'nombre_lote' => $cultivo->nombre ?? $cultivo->nombre_lote ?? "Lote {$cultivo->codigo}",
                    'etapa' => $cultivo->fase?->nombre ?? $cultivo->etapa ?? 'FLORACION',
                    'fotoperiodo' => $cultivo->fase?->fotoperiodo_sugerido ?? $cultivo->fotoperiodo ?? '12/12',
                    'semana_actual' => $semanaActual,
                    'dia_actual' => $diasTranscurridos,
                    'dias_totales_estimados' => $duracionEstimada,
                    'duracion_estimada' => $duracionEstimada,
                    'porcentaje_avance' => $porcentajeAvance,
                    'plantas_totales' => $plantasTotales,
                    'salud' => $cultivo->salud ?? 'OPTIMO',
                    'sala_id' => $cultivo->sala_id,
                    'sala_nombre' => $cultivo->sala?->nombre ?? 'Sala Principal',
                    'variedad_nombre' => $variedadNombre,
                    'banco' => $banco,
                    'cultivo_variedades' => $relVariedades,
                    'cultivoVariedades' => $relVariedades,
                    'sala' => $cultivo->sala,
                    'fase' => $cultivo->fase,
                    'estado_cultivo' => $cultivo->estadoCultivo,
                ];
            });

        // 3. Planificaciones operativas con tipo, estado y timeline
        $planificaciones = planificacion::with(['tipoPlanificacion', 'estadoPlanificacion', 'timeLine'])
            ->orderBy('fecha_inicio_planificacion', 'desc')
            ->limit(10)
            ->get();

        // 4. Eventos operativos (asociados a timeline o libres)
        $eventos = Evento::with(['tipoEvento', 'estadoEvento', 'timeLine', 'cultivo.sala', 'usuario'])
            ->orderBy('fecha_inicio_planificacion', 'asc')
            ->limit(25)
            ->get();

        // 5. Registros diarios de variables (VPD, CO2, temperaturas de aire/solución/sustrato, flujos)
        $registros = Registro::with(['cultivo.sala', 'user'])
            ->orderBy('fecha_registro', 'desc')
            ->limit(15)
            ->get();

        // 6. Lookups auxiliares para formularios rápidos
        $tiposPlanificacion = TipoPlanificacion::all();
        $estadosPlanificacion = EstadoPlanificacion::all();
        $tiposEvento = TipoEvento::all();
        $estadosEvento = EstadoEvento::all();
        $timelines = TimeLine::all();
        $totalVariedades = CatalogoVariedad::count();

        return Inertia::render('Dashboard1', [
            'salas' => $salas,
            'cultivos' => $cultivos,
            'planificaciones' => $planificaciones,
            'eventos' => $eventos,
            'registros' => $registros,
            'tiposPlanificacion' => $tiposPlanificacion,
            'estadosPlanificacion' => $estadosPlanificacion,
            'tiposEvento' => $tiposEvento,
            'estadosEvento' => $estadosEvento,
            'timelines' => $timelines,
            'totalVariedades' => $totalVariedades,
        ]);
    }

    /**
     * Alterna o avanza el estado de un evento operativo (Pendiente / En Ejecución / Realizado).
     */
    public function toggleEvento(Request $request, Evento $evento)
    {
        // Alternar estado de evento
        $estadoRealizado = EstadoEvento::where('nombre', 'REALIZADO')->first();
        $estadoPendiente = EstadoEvento::where('nombre', 'PENDIENTE')->first();

        if ($evento->estado_evento_id === optional($estadoRealizado)->id) {
            $evento->update(['estado_evento_id' => optional($estadoPendiente)->id ?? $evento->estado_evento_id]);
        } else {
            $evento->update(['estado_evento_id' => optional($estadoRealizado)->id ?? $evento->estado_evento_id]);
        }

        return redirect()->back();
    }

    /**
     * Guarda un nuevo Evento operativo (asociado a timeline o ad-hoc).
     */
    public function storeEvento(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio_planificacion' => 'required|date',
            'fecha_fin_planificacion' => 'nullable|date',
            'tipo_evento_id' => 'required|uuid',
            'estado_evento_id' => 'required|uuid',
            'cultivo_id' => 'required|uuid|exists:cultivos,id',
            'time_line_id' => 'nullable|uuid',
            'planta_id' => 'nullable|uuid',
        ]);

        $validated['usuario_id'] = auth()->id() ?? $request->user()?->id;

        Evento::create($validated);

        return redirect()->back()->with('success', 'Evento operativo programado con éxito.');
    }

    /**
     * Guarda un nuevo Registro diario de variables agronómicas y climáticas.
     */
    public function storeRegistro(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_registro' => 'required|date',
            'descripcion' => 'nullable|string',
            'cultivo_id' => 'required|uuid|exists:cultivos,id',
            'temperatura' => 'required|numeric',
            'humedad' => 'required|numeric',
            'vpd' => 'nullable|numeric',
            'co2' => 'nullable|numeric',
            'temperatura_solucion' => 'nullable|numeric',
            'temperatura_sustrato' => 'nullable|numeric',
            'flujo_hora_intraccion' => 'nullable|numeric',
            'flujo_hora_extracion' => 'nullable|numeric',
        ]);

        $validated['usuario_id'] = auth()->id() ?? $request->user()?->id;

        Registro::create($validated);

        return redirect()->back()->with('success', 'Registro agronómico guardado exitosamente.');
    }

    /**
     * Guarda una nueva Planificación con su TimeLine y duración estimada en horas.
     */
    public function storePlanificacion(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'duracion_hs' => 'nullable|numeric|min:0',
            'fecha_inicio_planificacion' => 'required|date',
            'fecha_fin_planificacion' => 'required|date|after_or_equal:fecha_inicio_planificacion',
            'tipo_planificacion_id' => 'required|uuid',
            'estado_planificacion_id' => 'required|uuid',
            'time_line_id' => 'nullable|uuid',
        ]);

        planificacion::create($validated);

        return redirect()->back()->with('success', 'Planificación registrada con éxito.');
    }

}



 