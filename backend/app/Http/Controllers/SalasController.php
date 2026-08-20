<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\TipoSala;
use App\Models\EstadoSala;


class SalasController extends Controller
{
    
    public function create()
{
    return Inertia::render('Salas/Create', [
        'tiposSala' => TipoSala::orderBy('nombre')->get(),
        'estadosSala' => EstadoSala::orderBy('nombre')->get(),
    ]);
}
    public function creates()
{
    return Inertia::render('Salas/Creates', [
        'tiposSala' => TipoSala::orderBy('nombre')->get(),
        'estadosSala' => EstadoSala::orderBy('nombre')->get(),
    ]);
}


    public function show()
{
    return Inertia::render('Salas/Creates', [
        'tiposSala' => TipoSala::orderBy('nombre')->get(),
        'estadosSala' => EstadoSala::orderBy('nombre')->get(),
    ]);
}



    //return Inertia::render('Organizaciones/Create');
    public function store(Request $request)
{
    $datos = $request->validate([
        'nombre' => ['required', 'string', 'max:100'],
        'descripcion' => ['nullable', 'string'],

        'posee_co2' => ['required', 'boolean'],
        'posee_extraccion' => ['required', 'boolean'],
        'posee_intraccion' => ['required', 'boolean'],
        'posee_ins_agua' => ['required', 'boolean'],

        'tipo_sala_id' => [
            'required',
            'uuid',
            'exists:tipo_salas,id',
        ],

        'estado_sala_id' => [
            'required',
            'uuid',
            'exists:estado_salas,id',
        ],

        'area' => ['required', 'numeric', 'min:0'],
        'altura' => ['required', 'numeric', 'min:0'],
        'volumen' => ['required', 'numeric', 'min:0'],
    ]);

    $datos['organizacion_id'] = session('organizacion_id');

    $sala = Sala::create($datos);

    return redirect()->route('salas.show', $sala);

}
}
