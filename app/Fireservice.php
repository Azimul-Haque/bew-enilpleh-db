<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fireservice extends Model
{
    public function district(){
        return $this->belongsTo('App\District');
    }
}
