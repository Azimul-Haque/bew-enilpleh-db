<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    public function fromDistrict()
    {
        return $this->belongsTo('App\District');
    }

    public function toDistrict()
    {
        return $this->belongsTo('App\District', 'to_district', 'district_id');
    }
}
