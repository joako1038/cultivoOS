<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoSala extends Model
{

use HasFactory, HasUuids;

    protected $table = 'tipo_salas';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];
      public function salas()
    {
        return $this->hasMany(Sala::class);
    }
}
