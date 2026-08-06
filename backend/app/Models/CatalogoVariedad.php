<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CatalogoVariedad extends Model
{
       use HasFactory, HasUuids;

    protected $table = 'catalogo_variedads';

    protected $fillable = [
        'nombre',
        'banco',
        'descripcion',
        'dias_ciclo',
        'tipo_variedad_id',
    ];

    protected $casts = [
        'dias_ciclo' => 'decimal:2',
    ];

    /**
     * Tipo de variedad asociado
     */
    public function tipoVariedad()
    {
        return $this->belongsTo(TipoVariedad::class);
    }

    public function cultivoVariedad()
{
        return $this->hasMany(CultivoVariedad::class);

}
}
