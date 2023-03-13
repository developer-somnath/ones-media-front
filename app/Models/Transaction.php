<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_intend_id',
        'payment_intent_client_secret',
        'order_id',
        'amount',
        'payment_method',
        'payment_status'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
