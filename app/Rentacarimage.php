<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rentacarimage extends Model
{
    public $timestamps = false;

    public function rentacar(){
        return $this->belongsTo('App\Rentacar');
    }
}
