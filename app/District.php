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
}
