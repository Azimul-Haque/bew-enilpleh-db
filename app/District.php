<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
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

    public function ambulances(){
        return $this->hasMany('App\Ambulance');
    }

    public function admins(){
        return $this->hasMany('App\Admin');
    }

    public function police(){
        return $this->hasMany('App\Police');
    }
}
