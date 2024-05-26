<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{

    public function district(){
        return $this->belongsTo('App\District');
    }

    public function upazilla(){
        return $this->belongsTo('App\Upazilla');
    }

    public function questionimage(){
        return $this->hasOne('App\Questionimage');
    }
    
    public function doctormedicaldepartments(){
        return $this->hasMany('App\Doctormedicaldepartment');
    }

    public function doctormedicalsymptoms(){
        return $this->hasMany('App\Doctormedicalsymptom');
    }

    public function doctorhospitals(){
        return $this->hasMany('App\Doctorhospital');
    }
}
