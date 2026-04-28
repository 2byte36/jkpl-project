<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'concert_id',
        'name',
        'price',
        'quota',
        'sales_start',
        'sales_end'
    ];

    public function concert()
    {
        return $this->belongsTo(Concert::class);
    }

    public function ticketOrders()
    {
        return $this->hasMany(TicketOrder::class);
    }
}
