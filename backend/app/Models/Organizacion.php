<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class Organizacion extends Model
{
      use HasFactory, HasUuids;

    protected $table = 'organizacions';

    protected $fillable = [
        'nombre',
        'descripcion',
        'localizacion',
    ];

      public function salas()
    {
        return $this->hasMany(Sala::class);
    }

    public function usuarios()
{
    return $this->hasMany(UsuarioOrganizacion::class,'organizacion_id');
}
}
