<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    public function medicaldepartment(){
        return $this->hasOne('App\Medicaldepartment');
    }
}
