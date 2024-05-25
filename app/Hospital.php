<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    public function district(){
        return $this->belongsTo('App\District');
    }

    public function upazilla(){
        return $this->belongsTo('App\Upazilla');
    }

    public function doctorhospitals(){
        return $this->hasMany('App\Doctorhospital');
    }
}
