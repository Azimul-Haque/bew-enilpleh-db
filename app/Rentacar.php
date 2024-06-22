<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rentacar extends Model
{
    public function district(){
        return $this->belongsTo('App\District');
    }

    public function rentacarimage(){
        return $this->hasOne('App\Rentacarimage');
    }
}
