<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use App\Models\Cultivo;
use App\Models\CatalogoVariedad;
use App\Models\ActualizacionBitacora;
use App\Models\TareaPendiente;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $salas = Sala::with(['equipamientos'])
            ->orderBy('nombre')
            ->get();

        $cultivos = Cultivo::with([
            'catalogoVariedad.tipoVariedad',
            'sala'
        ])
            ->orderBy('created_at', 'desc')
            ->get();

        $bitacoras = [];

        $tareas = [];

        $totalVariedades = CatalogoVariedad::count();

        return Inertia::render('Dashboard1', [
            'salas' => $salas,
            'cultivos' => $cultivos,
            'bitacoras' => $bitacoras,
            'tareas' => $tareas,
            'totalVariedades' => $totalVariedades,
        ]);
    }

    public function toggleTarea(
        Request $request,
        TareaPendiente $tarea
    ) {
        $tarea->update([
            'completada' => !$tarea->completada,
        ]);

        return redirect()->back();
    }

    public function storeBitacora(Request $request)
    {
        $validated = $request->validate([
            'cultivo_id' => 'required|uuid|exists:cultivos,id',
            'tipo' => 'required|string|in:RIEGO,PODA,CLIMA,NUTRICION,PREVENTIVO,TRANSPLANTE,COSECHA,OBSERVACION',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'ec' => 'nullable|numeric|min:0|max:5',
            'ph' => 'nullable|numeric|min:0|max:14',
            'temp_c' => 'nullable|numeric|min:-10|max:60',
            'hum_pct' => 'nullable|numeric|min:0|max:100',
        ]);

        $validated['autor'] = auth()->user()->name ?? 'Operador Agronómico';

        ActualizacionBitacora::create($validated);

        return redirect()
            ->back()
            ->with('success', 'Registro de bitácora guardado con éxito.');
    }
}