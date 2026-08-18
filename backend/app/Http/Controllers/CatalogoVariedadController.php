<?php

namespace App\Http\Controllers;
use Inertia\Inertia;

use Illuminate\Http\Request;
use App\Models\TipoVariedad;

class CatalogoVariedadController extends Controller
{
      public function index()
    {
        $tiposVariedad = TipoVariedad::all();

        return Inertia::render('CatalogoVariedades/Create', [
            'tiposVariedad' => $tiposVariedad,
        ]);
    }

    /**
     * Almacena una nueva variedad en la base de datos PostgreSQL.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'           => ['required', 'string', 'max:255'],
            'banco'            => ['nullable', 'string', 'max:255'],
            'tipo_variedad_id' => ['required', 'uuid', 'exists:tipo_variedads,id'],
            'dias_ciclo'       => ['nullable', 'numeric', 'min:1', 'max:365'],
            'descripcion'      => ['nullable', 'string'],
        ]);

        // HasUuids genera automáticamente el UUID
        $variedad = CatalogoVariedad::create($validated);

        return redirect()->route('catalogo-variedades.index')
            ->with('success', 'Variedad ' . $variedad->nombre . ' registrada exitosamente.');
    }
}
