<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctormedicalsymptom extends Model
{
    public function doctor(){
        return $this->belongsTo('App\Doctor');
    }

    public function medicalsymptom(){
        return $this->belongsTo('App\Medicalsymptom');
    }
}
