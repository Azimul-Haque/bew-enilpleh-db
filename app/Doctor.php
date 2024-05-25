<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    public function doctormedicaldepartments(){
        return $this->hasMany('App\Doctormedicaldepartment');
    }

    public function doctormedicalsymptoms(){
        return $this->hasMany('App\Doctormedicalsymptom');
    }
}
