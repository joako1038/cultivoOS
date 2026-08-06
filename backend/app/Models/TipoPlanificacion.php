<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPlanificacion extends Model
{


 public function planificaciones()
    {
        return $this->hasMany(Planificacion::class);
    }
}
