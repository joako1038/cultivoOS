<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Registro extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'registros';

    protected $fillable = [
        'nombre',
        'fecha_registro',
        'descripcion',
        'vpd',
        'co2',
        'temperatura',
        'humedad',
        'temperatura_solucion',
        'temperatura_sustrato',
        'flujo_hora_intraccion',
        'flujo_hora_extracion',
        'cultivo_id',
        'usuario_id',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',

        'vpd' => 'decimal:2',
        'co2' => 'decimal:2',
        'temperatura' => 'decimal:2',
        'humedad' => 'decimal:2',
        'temperatura_solucion' => 'decimal:2',
        'temperatura_sustrato' => 'decimal:2',
        'flujo_hora_intraccion' => 'decimal:2',
        'flujo_hora_extracion' => 'decimal:2',
    ];


    /**
     * El registro pertenece a un cultivo
     */
    public function cultivo()
    {
        return $this->belongsTo(Cultivo::class);
    }


    /**
     * Usuario que creó el registro
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
