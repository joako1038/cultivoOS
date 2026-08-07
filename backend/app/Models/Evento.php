<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class Evento extends Model
{
   use HasFactory, HasUuids;

     protected $table = 'eventos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_inicio_planificacion',
        'fecha_fin_planificacion',
        'estado_evento_id',
        'tipo_evento_id',
        'usuario_id',
        'time_line_id',
        'cultivo_id',
        'planta_id',
    ];
    protected $casth =[
        'fecha_inicio_planificacion'=>'date',
        'fecha_fin_planificacion'=>'date',

    ];

    public function estadoEvento(){
        return $this->belongsTo(EstadoEvento::Class);
    }  

    public function tipoEvento(){
        return $this->belongsTo(TipoEvento::Class);
    }    
    public function usuario(){
        return $this->belongsTo(User::Class,'usuario_id');
    }  

     public function timeLine(){
        return $this->belongsTo(TimeLine::Class);
    }  

    public function cultivo(){
        return $this->belongsTo(Cultivo::Class);
    }  

    public function planta(){
        return $this->belongsTo(Planta::Class);
    }  


    
}
