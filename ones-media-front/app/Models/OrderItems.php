<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;
    protected $table = "order_has_items";
    protected $fillable = [
        'order_id',
        'item_id',
        'quantity',
        'paid_amount',
        'discount_amount',
        'type',
        'product_type',
        'item_amount'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function show()
    {
        return $this->belongsTo(Shows::class, 'item_id');
    }
    public function sample()
    {
        return $this->belongsTo(SampleFiles::class, 'item_id');
    }

    /* public function scopeItem($query)
    {
        return $query
              ->when($this->product_type === 1,function($q){
                  return $q->with('shows');
             })
             ->when($this->product_type === 2,function($q){
                  return $q->with('sampleFiles');
             });
    } */


}
