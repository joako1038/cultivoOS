<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class EstadoCultivo extends Model
{

 use HasFactory, HasUuids;

    protected $table = 'estado_cultivos';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

   
    public function cultivos()
    {
        return $this->hasMany(Cultivo::class);
    }
}
