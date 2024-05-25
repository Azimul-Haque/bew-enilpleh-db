<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctorimage extends Model
{
    public function qustion(){
        return $this->belongsTo('App\Question');
    }
}
