<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicalsymptom extends Model
{
    public $timestamps = false;

    public function doctormedicalsymptom(){
        return $this->hasOne('App\Doctormedicalsymptom');
    }
}
