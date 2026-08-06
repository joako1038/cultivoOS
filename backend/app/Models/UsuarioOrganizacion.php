<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UsuarioOrganizacion extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'usuario_organizacions';


    protected $fillable = [
        'es_propietario',
        'invitado_por',
        'organizacion_id',
        'usuario_id',
        'rol_id',
        'estado_usuario_organizacion_id',
    ];


    protected $casts = [
        'es_propietario' => 'boolean',
    ];


    /**
     * Usuario asociado
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }


    /**
     * Organización asociada
     */
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class);
    }


    /**
     * Rol dentro de la organización
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }


    /**
     * Estado de la relación usuario-organización
     */
    public function estado()
    {
        return $this->belongsTo(
            EstadoUsuarioOrganizacion::class,
            'estado_usuario_organizacion_id'
        );
    }


    /**
     * Usuario que realizó la invitación
     */
    public function invitadoPor()
    {
        return $this->belongsTo(
            Usuario::class,
            'invitado_por'
        );
    }
}