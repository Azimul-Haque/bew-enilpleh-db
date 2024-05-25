<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicalsymptom extends Model
{
    public $timestamps = false;

    public function doctormedicalsymptoms(){
        return $this->hasMany('App\Doctormedicalsymptom');
    }
}
