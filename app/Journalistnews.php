<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Journalistnews extends Model
{
    public function journalist(){
        return $this->belongsTo('App\Journalist');
    }
}
