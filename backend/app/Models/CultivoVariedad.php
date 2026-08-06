<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class CultivoVariedad extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'cultivo_variedads';
    protected $fillable = [
        'descripcion',
        'cantidad_plantas',
        'fecha_inicio',
        'catalogo_variedad_id'
    ];

    protected $casts = [
        'cantidad_plantas' => 'decimal:2',
        'fecha_inicio'=>'date',
    ];

    public function catalogoVaridad()
    {
        return $this->belongsTO(CatalogoVaridad::Class);
    }

     public function plantas()
    {
        return $this->hasMany(Planta::class);
    }
    
}
