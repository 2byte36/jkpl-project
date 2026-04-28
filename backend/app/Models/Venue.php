<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $table = 'venues';
    protected $fillable = [
        'name', 
        'city_id', 
        'latitude', 
        'longitude'
    ];
    public $timestamps = false;

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function concerts()
    {
        return $this->hasMany(Concert::class, 'venue_id');
    }
}
