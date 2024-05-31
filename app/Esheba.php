<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Esheba extends Model
{
    public function district(){
        return $this->belongsTo('App\District');
    }

    public function upazilla(){
        return $this->belongsTo('App\Upazilla');
    }

    public function eshebaimage(){
        return $this->hasOne('App\Eshebaimage');
    }
}
