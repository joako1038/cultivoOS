<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sala extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'salas';

    protected $fillable = [
        'nombre',
        'area',
        'altura',
        'volumen',
        'posee_intraccion',
        'posee_extraccion',
        'posee_co2',
        'posee_ins_agua',
        'tipo_sala_id',
        'estado_sala_id',
        'organizacion_id',
    ];


    protected $casts = [
        'area' => 'decimal:2',
        'altura' => 'decimal:2',
        'volumen' => 'decimal:2',

        'posee_intraccion' => 'boolean',
        'posee_extraccion' => 'boolean',
        'posee_co2' => 'boolean',
        'posee_ins_agua' => 'boolean',
    ];


    /**
     * Tipo de sala
     */
    public function tipoSala()
    {
        return $this->belongsTo(TipoSala::class);
    }


    /**
     * Estado actual de la sala
     */
    public function estadoSala()
    {
        return $this->belongsTo(EstadoSala::class);
    }


    /**
     * Organización propietaria
     */
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class);
    }


    /**
     * Cultivos realizados en esta sala
     */
    public function cultivos()
    {
        return $this->hasMany(Cultivo::class);
    }
     public function equipamientos()
    {
    return $this->hasMany(
        Equipamiento::class,
        'sala_id'
    );
    }
}

