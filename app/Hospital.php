<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    public function editors()
    {
        return $this->morphToMany(User::class, 'accessible', 'editor_access');
    }
    
    public function district(){
        return $this->belongsTo('App\District');
    }

    public function upazilla(){
        return $this->belongsTo('App\Upazilla');
    }

    public function doctorhospitals(){
        return $this->belongsToMany('App\Doctorhospital');
    }

    public function hospitalimages(){
        return $this->hasMany('App\Hospitalimage');
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

    // Get all hospitals that consider this hospital as their branch
    public function mutualBranches()
    {
        return $this->belongsToMany(
            Hospital::class,
            'hospital_branches',
            'branch_id',
            'hospital_id'
        )->withTimestamps();
    }

    // Get all mutual branch relationships (both directions)
    public function allBranches()
    {
        return $this->branches->merge($this->mutualBranches);
    }
}
