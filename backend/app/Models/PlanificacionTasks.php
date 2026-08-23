<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class PlanificacionTasks extends Model
{
/**
 * Tarea planificada asociada a una Planificación
 */
    use HasFactory, HasUuids;

    protected $table = 'planificacion_tasks';

    protected $fillable = [
        'planificacion_id',
        'titulo',
        'descripcion',
        'semana_sugerida',
        'dia_ciclo_sugerido',
        'duracion_estimada_hs',
        'tipo_accion',
        'completada',
        'evento_asociado_id'
    ];

    protected $casts = [
        'completada' => 'boolean',
    ];

    public function planificacion(): BelongsTo
    {
        return $this->belongsTo(Planificacion::class);
    }

    public function eventoAsociado(): BelongsTo
    {
        return $this->belongsTo(
            Evento::class,
            'evento_asociado_id'
        );
    }
}
