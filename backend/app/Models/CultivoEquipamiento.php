<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
/**
 * Modelo Intermedio & Pivot de Asignación Histórica: CultivoEquipamiento
 * Permite encapsular reglas de negocio, scopes y validación de sala física.
 */
class CultivoEquipamiento extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'cultivo_equipamientos';

    protected $fillable = [
        'cultivo_id',
        'equipamiento_id',
        'fecha_inicio',
        'fecha_fin',
        'notas'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    protected static function booted(): void
    {
        // REGLA DE NEGOCIO: Un equipamiento solo puede asignarse a un cultivo si ambos pertenecen a la misma sala.
        static::saving(function (CultivoEquipamiento $asignacion) {
            $equipamiento = Equipamiento::find($asignacion->equipamiento_id);
            $cultivo = Cultivo::find($asignacion->cultivo_id);

            if ($equipamiento && $cultivo && $equipamiento->sala_id !== $cultivo->sala_id) {
                throw new InvalidArgumentException(
                    "Regla de Sala violada: El equipamiento {$equipamiento->codigo_inventario} (Sala: {$equipamiento->sala_id}) no pertenece a la misma sala del cultivo {$cultivo->nombre} (Sala: {$cultivo->sala_id})."
                );
            }
        });
    }

    public function cultivo(): BelongsTo
    {
        return $this->belongsTo(Cultivo::class);
    }

    public function equipamiento(): BelongsTo
    {
        return $this->belongsTo(Equipamiento::class);
    }

    // Scopes de utilidad
    public function scopeActivos($query)
    {
        return $query->whereNull('fecha_fin');
    }
}

