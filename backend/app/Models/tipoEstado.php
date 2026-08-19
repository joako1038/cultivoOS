<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class tipoEstado extends Model
{
   use HasFactory, HasUuids;

    protected $table = 'tipo_estados';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}
