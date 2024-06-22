<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rab extends Model
{
    public function district(){
        return $this->belongsTo('App\District');
    }
}
