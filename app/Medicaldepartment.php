<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicaldepartment extends Model
{
    public $timestamps = false;

    public function doctors(){
        return $this->hasMany('App\Doctor');
    }

    public function examcategory(){
        return $this->belongsTo('App\Examcategory');
    }
}
