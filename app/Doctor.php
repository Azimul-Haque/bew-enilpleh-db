<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    public function doctormedicaldepartment(){
        return $this->hasOne('App\Doctormedicaldepartment');
    }
}
