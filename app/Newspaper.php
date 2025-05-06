<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Newspaper extends Model
{
    public function district(){
        return $this->belongsTo('App\District');
    }
    
    public function newspaperimage(){
        return $this->hasOne('App\Newspaperimage');
    }
}
