<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class TimeLine extends Model
{
       use HasFactory, HasUuids;

    protected $table = 'time_lines';


    protected $fillable = [
        'nombre',
        'fecha_prevista_fin',
        'fecha_real_fin',
        'fecha_inicio',
        'cultivo_id',
    ];


    protected $casts = [
        'fecha_prevista_fin' => 'datetime',
        'fecha_real_fin' => 'datetime',
        'fecha_inicio' => 'datetime',
    ];


    /**
     * El timeline pertenece a un cultivo
     */
    public function cultivo()
    {
        return $this->belongsTo(Cultivo::class);
    }
     
    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }
}
