<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coaching extends Model
{
    public function editors()
    {
        return $this->morphToMany(User::class, 'accessible', 'editor_access');
    }
    
    public function district(){
        return $this->belongsTo('App\District');
    }

    public function coachingimages(){
        return $this->hasMany('App\Coachingimage');
    }
}
