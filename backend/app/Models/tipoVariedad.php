<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class tipoVariedad extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tipo_variedads';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

     public function catalogoVariedades()
    {
        return $this->hasMany(CatalogoVariedad::class);
    }
}
