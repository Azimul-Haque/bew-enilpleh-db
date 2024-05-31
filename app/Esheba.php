<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Esheba extends Model
{
    public function eshebaimage(){
        return $this->hasOne('App\Eshebaimage');
    }
}
