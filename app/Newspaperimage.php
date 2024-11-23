<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Newspaperimage extends Model
{
    public $timestamps = false;
    
    public function newspaper(){
        return $this->belongsTo('App\Newspaper');
    }
}
