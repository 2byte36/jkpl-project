<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketOrder extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'ticket_id',
        'order_detail_id',
        'quantity'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class);
    }
}
