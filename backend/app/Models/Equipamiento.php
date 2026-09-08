<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Equipamiento extends Model
{
   /**
 * Equipamiento físico instalado en una Sala
 */
    use HasFactory, HasUuids;

    protected $table = 'equipamientos';

    protected $fillable = [
        'sala_id',
        'tipo_equipamiento_id',
        'nombre',
        'codigo_inventario',
        'marca',
        'modelo',
        'especificaciones',
        'estado',
        'calibrado',
        'fecha_ultima_calibracion',
        'notas',
        'cultivo_id',
    ];

    protected $casts = [
        'calibrado' => 'boolean',
        'fecha_ultima_calibracion' => 'date',
        'especificaciones' => 'array',
    ];

    public function sala(): BelongsTo
    {
        return $this->belongsTo(Sala::class);
    }

    public function tipoEquipamiento(): BelongsTo
    {
        return $this->belongsTo(TipoEquipamiento::class, 'tipo_equipamiento_id');
    }



    public function asignaciones(): HasMany
    {
        return $this->hasMany(CultivoEquipamiento::class, 'equipamiento_id');
    }


public function cultivos(): BelongsToMany
{
    return $this->belongsToMany(
        Cultivo::class,
        'cultivo_equipamientos',
        'equipamiento_id',
        'cultivo_id'
    )->withPivot([
        'fecha_inicio',
        'fecha_fin',
        'notas',
    ]);
}
}


