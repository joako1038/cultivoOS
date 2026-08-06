<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeLine extends Model
{
     
    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }
}
