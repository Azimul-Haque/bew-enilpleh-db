<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eshebaimage extends Model
{
    public function esheba(){
        return $this->belongsTo('App\Esheba');
    }
}
