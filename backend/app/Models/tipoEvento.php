<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tipoEvento extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tipo_evento';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];
     
    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }
}
