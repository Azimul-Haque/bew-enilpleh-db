<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicaldepartment extends Model
{
    public $timestamps = false;

    public function doctormedicaldepartment(){
        return $this->hasMany('App\Doctormedicaldepartment');
    }
}
