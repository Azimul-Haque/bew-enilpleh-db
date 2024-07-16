<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    public function district()
    {
        return $this->belongsTo('App\District', 'district_id', 'district_id');
    }

    public function toDistrict()
    {
        return $this->belongsTo('App\District', 'to_district', 'district_id');
    }
}
