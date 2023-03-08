<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    use HasFactory;
    protected $table = "oder_has_adresses";
    protected $fillable = [
        'order_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'street_address',
        'address_line_2',
        'zip_code',
        'city',
        'country_id',
        'state_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'user_id');
    }
    public function country()
    {
        return $this->belongsTo(Countries::class, 'country_id');
    }
    public function state()
    {
        return $this->belongsTo(States::class, 'state_id');
    }

}
