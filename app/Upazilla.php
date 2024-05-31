<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upazilla extends Model
{
    public $timestamps = false;

    public function hospitals(){
        return $this->hasMany('App\Hospital');
    }

    public function doctors(){
        return $this->hasMany('App\Doctor');
    }

    public function blooddonors(){
        return $this->hasMany('App\Blooddonor');
    }
}
