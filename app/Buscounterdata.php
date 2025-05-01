<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buscounterdata extends Model
{
    public function bus(){
        return $this->belongsTo('App\Bus');
    }
    
    public function bus(){
        return $this->belongsTo('App\Bus');
    }
}
