<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;
    protected $table = "order_has_statuses";
    protected $fillable = [
        'order_id',
        'order_status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'user_id');
    }

}
