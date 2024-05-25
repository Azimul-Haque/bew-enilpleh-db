<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctormedicaldepartment extends Model
{
    public function doctor(){
        return $this->belongsTo('App\Doctor');
    }

    public function medicaldepartment(){
        return $this->belongsTo('App\Medicaldepartment');
    }
}
