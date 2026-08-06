<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPlanificacion extends Model
{
use HasFactory, HasUuids;

    protected $table = 'tipo_planificacions';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

 public function planificaciones()
    {
        return $this->hasMany(Planificacion::class);
    }
}
