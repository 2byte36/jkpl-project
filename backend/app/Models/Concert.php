<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concert extends Model
{
    protected $table = 'concerts';
    protected $fillable = [
        'name',
        'description',
        'concert_start',
        'concert_end',
        'venue_id',
        'link_poster',
        'link_venue'
    ];
    public $timestamps = false;

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'concert_genres', 'concert_id', 'genre_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
