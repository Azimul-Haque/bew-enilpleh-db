<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctorserial extends Model
{
    public function doctor(){
        return $this->belongsTo('App\Doctor');
    }

    public function hospital(){
        return $this->belongsTo('App\Hospital');
    }
}
