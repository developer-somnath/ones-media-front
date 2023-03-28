<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'order_alt_id',
        'oder_amount',
        'paid_amount',
        'discount_amount',
        'shipping_cost',
        'type',
        'payment_status',
        'shipment_status',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function items()
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }
    public function status()
    {
        return $this->hasMany(OrderStatus::class, 'order_id');
    }
    public function address()
    {
        return $this->hasMany(OrderAddress::class, 'order_id');
    }
    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'order_id');
    }
}
