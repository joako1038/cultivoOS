<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoSala extends Model
{
      public function salas()
    {
        return $this->hasMany(Sala::class);
    }
}
