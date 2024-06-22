<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rab extends Model
{
    public function district(){
        return $this->belongsTo('App\District');
    }

    public function rabbattalion(){
        return $this->belongsTo('App\Rabbattalion');
    }
}
