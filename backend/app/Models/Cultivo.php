<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Cultivo extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'cultivos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'duracion_estimada',
        'sala_id',
        'fase_id',
        'estado_cultivo_id',
        'usuario_responsable_id'
    ];

    protected $casts = [
        'duracion_estimada' => 'decimal:2',
    ];


    public function sala(){
        return $this->belongsTo(Sala::class);
    }
    public function fase(){
        return $this->belongsTO(FaseCultivo::class,'fase_id');
    }

    public function estadoCultivo(){
        return $this->belongsTO(EstadoCultivo::class);
    }

    public function usuarioResponsable(){
        return $this->belongsTO(User::class,'usuario_responsable_id');
    }
     
    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }

    public function registros()
    {
        return $this->hasMany(Registro::class);
    }
    public function timeLines()
{
    return $this->hasMany(TimeLine::class);
}
}
