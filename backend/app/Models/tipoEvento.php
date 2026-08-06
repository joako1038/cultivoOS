<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tipoEvento extends Model
{
     
    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }
}
