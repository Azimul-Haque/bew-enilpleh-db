<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    public function fromDistrict()
    {
        return $this->belongsTo('App\District', 'from_district', 'district_id');
    }

    public function toDistrict()
    {
        return $this->belongsTo('App\District', 'to_district', 'district_id');
    }
}
