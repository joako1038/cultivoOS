<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EstadoSala extends Model
{
    use HasFactory, HasUuids;

     protected $table = 'estado_salas';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

     public function salas()
    {
        return $this->hasMany(Sala::class);
    }
}
