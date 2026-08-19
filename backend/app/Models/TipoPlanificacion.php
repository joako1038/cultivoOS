<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
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
