<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class planificacion extends Model
{
  use HasFactory, HasUuids;

     protected $table = 'planificacions';

    protected $fillable = [
        'nombre',
        'descripcion',
        'duracion_hs',
        'fecha_inicio_planificacion',
        'fecha_fin_planificacion',
        'tipo_planificacion_id',
        'estado_planificacion_id',
        'time_line_id',
    ];
    protected $casts = [
        'duracion_estimada' => 'decimal:1',
        'fecha_inicio_planificacion'=>'datetime',
        'fecha_fin_planificacion'=>'datetime',
    ];

    public function tipoPlanificacion(){
        return $this->belongsTo(TipoPlanificacion::Class);
    }
    public function estadoPlanificacion(){
        return $this->belongsTo(EstadoPlanificacion::Class);
    }
    public function timeLine(){
        return $this->belongsTo(TimeLine::Class);
    }
}
