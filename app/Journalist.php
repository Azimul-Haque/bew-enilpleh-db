<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Journalist extends Model
{
    protected $casts = [
        'top_news_links' => 'array',
    ];
    
    public function district(){
        return $this->belongsTo('App\District');
    }
}
