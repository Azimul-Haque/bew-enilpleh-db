<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rentacarimage extends Model
{
    public $timestamps = false;

    public function ambulance(){
        return $this->belongsTo('App\Ambulance');
    }
}
