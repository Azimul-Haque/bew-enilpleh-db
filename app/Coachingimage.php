<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coachingimage extends Model
{
    public function coaching(){
        return $this->belongsTo('App\Coaching');
    }
}
