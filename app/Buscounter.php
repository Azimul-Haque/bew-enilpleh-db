<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buscounter extends Model
{
    public function buscounterdatas(){
        return $this->hasMany('App\Buscounterdata');
    }
}
