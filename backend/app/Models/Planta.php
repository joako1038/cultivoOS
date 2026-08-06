<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class Planta extends Model
{

  use HasFactory, HasUuids;

     protected $table = 'plantas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_inicio',
        'estado_planta_id',
        'cultivo_variedad_id'
    ];
 
    protected $casts =[
        'fecha_inicio'=>'datetime',
    ];

    public function estadoPlanta(){
        return $this->belongsTo(estadoPlanta::Class);
    }

    public function cultivoVariedad(){
        return $this->belongsTo(CultivoVariedad::Class);
    }
    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }
}
