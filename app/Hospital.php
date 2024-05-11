<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    public function districts(){
        return $this->belongsTo('App\District');
    }

    public function upazillas(){
        return $this->belongsTo('App\Upazilla');
    }
}
