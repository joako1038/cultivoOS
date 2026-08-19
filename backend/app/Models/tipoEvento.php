<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class tipoEvento extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tipo_eventos';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];
     
    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }
}
