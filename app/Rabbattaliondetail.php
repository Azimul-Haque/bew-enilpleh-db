<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rabbattaliondetail extends Model
{
    public function rabbattalion(){
        return $this->belongsTo('App\Rabbattalion');
    }
}
