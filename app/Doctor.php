<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{

    protected $casts = [
            'schedule' => 'array',
        ];

    public function district(){
        return $this->belongsTo('App\District');
    }

    public function upazilla(){
        return $this->belongsTo('App\Upazilla');
    }

    public function doctorimage(){
        return $this->hasOne('App\Doctorimage');
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
