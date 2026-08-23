<?php

namespace AppHttpControllers;

use AppModelsSala;
use AppModelsCultivo;
use AppModelsCatalogoVariedad;
use AppModelsRegistro;
use AppModelsplanificacion;
use AppModelsEvento;
use AppModelsTipoPlanificacion;
use AppModelsEstadoPlanificacion;
use AppModelsTipoEvento;
use AppModelsEstadoEvento;
use AppModelsTimeLine;
use IlluminateHttpRequest;
use InertiaInertia;
use InertiaResponse;

class DashboardController extends Controller
{
    /**
     * Muestra el Dashboard principal de CultivoOS cargando:
     * - Salas y Cultivos activos
     * - Planificaciones activas y su TimeLine
     * - Eventos operativos (asociados o no a planificaciones)
     * - Registros diarios de telemetría y variables agronómicas
     */
    public function index(): Response
    {
        // 1. Salas con parámetros climáticos
        $salas = Sala::with(['equipamientos'])
            ->orderBy('nombre')
            ->get();

        // 2. Cultivos activos
        $cultivos = Cultivo::with(['catalogoVariedad.tipoVariedad', 'sala'])
            ->where('etapa', '!=', 'FINALIZADO')
            ->orderBy('created_at', 'desc')
            ->get();

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

        return Inertia::render('Dashboard', [
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
            'vpd' => 'nullable|numeric|between:0,10',
            'co2' => 'nullable|numeric|between:0,5000',
            'temperatura' => 'nullable|numeric|between:-10,60',
            'humedad' => 'nullable|numeric|between:0,100',
            'temperatura_solucion' => 'nullable|numeric|between:0,50',
            'temperatura_sustrato' => 'nullable|numeric|between:0,50',
            'flujo_hora_intraccion' => 'nullable|numeric|min:0',
            'flujo_hora_extracion' => 'nullable|numeric|min:0',
        ]);

        $validated['usuario_id'] = auth()->id() ?? $request->user()?->id;

        Registro::create($validated);

        return redirect()->back()->with('success', 'Registro diario de variables guardado correctamente.');
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
