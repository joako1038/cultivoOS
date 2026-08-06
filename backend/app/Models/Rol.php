<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Rol extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'rols';

    protected $fillable = [
        'nombre',
        'descripcion',
       
    ];

    public function usuariosOrganizaciones()
{
    return $this->hasMany(UsuarioOrganizacion::class);
}
}
