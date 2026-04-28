<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $table = 'genres';
    protected $fillable = [
        'name'
    ];
    public $timestamps = false;

    public function concerts()
    {
        return $this->belongsToMany(Concert::class, 'concert_genres', 'genre_id', 'concert_id');
    }
}
