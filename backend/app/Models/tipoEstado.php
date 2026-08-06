<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tipoEstado extends Model
{
   use HasFactory, HasUuids;

    protected $table = 'tipo_estados';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}
