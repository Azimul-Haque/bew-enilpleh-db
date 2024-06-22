<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rabbattalion extends Model
{
    public function rabbattaliondetails(){
        return $this->hasMany('App\Rabbattaliondetail');
    }

    public function rabs(){
        return $this->hasMany('App\Rab');
    }
}
