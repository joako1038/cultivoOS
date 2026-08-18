<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Equipamiento extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'equipamientos';

    protected $fillable = [
        'nombre',
        'marca',
        'modelo',
        'numero_serie',
        'descripcion',
        'sala_id',
        'tipo_equipamiento_id',
    ];

    public function sala()
    {
        return $this->belongsTo(
            Sala::class,
            'sala_id'
        );
    }

    public function tipo()
    {
        return $this->belongsTo(
            TipoEquipamiento::class,
            'tipo_equipamiento_id'
        );
    }

   

}