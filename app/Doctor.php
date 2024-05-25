<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    public function medicaldepartments(){
        return $this->hasOne('App\Medicaldepartment');
    }
}
