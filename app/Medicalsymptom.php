<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicalsymptom extends Model
{
    public $timestamps = false;

    public function doctormedicaldepartment(){
        return $this->hasOne('App\Doctormedicaldepartment');
    }
}
