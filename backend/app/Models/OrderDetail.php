<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'user_id',
        'order_time',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticketOrders()
    {
        return $this->hasMany(TicketOrder::class);
    }
}
