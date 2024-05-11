<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    public $timestamps = false;

    public function hospital(){
        return $this->hasMany('App\Hospital');
    }
}
