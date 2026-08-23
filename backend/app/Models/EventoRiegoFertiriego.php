<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class EventoRiegoFertiriego extends Model
{
 /**
 * Registro de Evento de Riego / Fertirriego
 */
    use HasFactory, HasUuids;

    protected $table = 'eventos_riego_fertirriego';

    protected $fillable = [
        'cultivo_id',
        'fecha_hora',
        'tipo_accion',
        'estrategia_utilizada',
        'metodo_aplicacion',
        'volumen_programado_l',
        'volumen_aplicado_l',
        'volumen_drenado_l',
        'porcentaje_drenaje_calculado',
        'ec_entrada_ms',
        'ec_drenaje_ms',
        'ph_entrada',
        'ph_drenaje',
        'vwc_antes_pct',
        'vwc_despues_pct',
        'vpd_kpa',
        'temperatura_c',
        'fertilizantes_receta',
        'estado',
        'caudal_nominal_lph',
        'caudal_real_lph',
        'calibracion_valida',
        'alertas_detectadas',
        'observaciones'
    ];

    protected $casts = [
        'alertas_detectadas' => 'array',
        'calibracion_valida' => 'boolean',
        'fertilizantes_receta' => 'array',
        'fecha_hora' => 'datetime',
    ];

    public function cultivo(): BelongsTo
    {
        return $this->belongsTo(Cultivo::class);
    }

}
