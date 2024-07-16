<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    public function fromDistrict()
    {
        return $this->belongsTo(District::class, 'from_district', 'district_id');
    }

    public function toDistrict()
    {
        return $this->belongsTo(District::class, 'to_district', 'district_id');
    }
}
