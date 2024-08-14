<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blooddonormember extends Model
{
    public function blooddonor(){
        return $this->belongsTo('App\Blooddonor');
    }
}
