<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function registros()
    {
        return $this->hasMany(Registro::class,'usuario_id');
    }


    public function cultivos()
    {
        return $this->hasMany(Cultivo::class,'usuario_responsable_id');
    }

     
    public function eventos()
    {
        return $this->hasMany(Evento::class,'usuario_id');
    }

    public function organizaciones()
{
    return $this->hasMany(UsuarioOrganizacion::class);
}

}

















