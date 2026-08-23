<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class ConfiguracionRiegoCultivo extends Model
{
    /**
 * Configuración de Riego del Cultivo
 */
    use HasFactory, HasUuids;

    protected $table = 'configuraciones_riego_cultivo';

    protected $fillable = [
        'cultivo_id',
        'estrategia',
        'metodo_aplicacion',
        'tipo_sustrato',
        'volumen_maceta_l',
        'tipo_maceta',
        'fertilizantes_receta',
        'vwc_minimo_pct',
        'vwc_objetivo_pct',
        'vwc_maximo_pct',
        'histeresis_pct',
        'drenaje_objetivo_pct',
        'ec_entrada_objetivo_ms',
        'ec_drenaje_max_ms',
        'ph_entrada_objetivo',
        'pulsos_diarios_sugeridos',
        'volumen_por_pulso_ml',
        'volumen_max_diario_l',
        'tiempo_minimo_entre_pulsos_min',
        'kc_cultivo',
        'modo_automatico',
        'modo_pausado'
    ];

    protected $casts = [
        'modo_automatico' => 'boolean',
        'modo_pausado' => 'boolean',
        'fertilizantes_receta' => 'array',
    ];

    public function cultivo(): BelongsTo
    {
        return $this->belongsTo(Cultivo::class);
    }

}
