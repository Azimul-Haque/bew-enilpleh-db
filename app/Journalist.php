<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Journalist extends Model
{
    public function district(){
        return $this->belongsTo('App\District');
    }

    public function hospitalimages(){
        return $this->hasMany('App\Hospitalimage');
    }
}
