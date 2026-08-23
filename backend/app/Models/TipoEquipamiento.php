<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TipoEquipamiento extends Model
{
      use HasFactory, HasUuids;

    protected $table = 'tipo_equipamientos';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion'
    ];

    public function equipamientos(): HasMany
    {
        return $this->hasMany(Equipamiento::class, 'tipo_equipamiento_id');
    }
    
}