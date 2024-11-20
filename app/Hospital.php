<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    public function district(){
        return $this->belongsTo('App\District');
    }

    public function upazilla(){
        return $this->belongsTo('App\Upazilla');
    }

    public function doctorhospitals(){
        return $this->hasMany('App\Doctorhospital');
    }

    public function branches()
        {
            return $this->belongsToMany(
                Hospital::class,
                'hospital_branches',
                'hospital_id',
                'branch_id'
            );
        }

        // Get all parent hospitals of which this hospital is a branch
        public function parentHospitals()
        {
            return $this->belongsToMany(
                Hospital::class,
                'hospital_branches',
                'branch_id',
                'hospital_id'
            );
        }
}
