<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    public function doctorimage(){
        return $this->hasOne('App\Doctorimage');
    }
}
