<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = "sales";
    protected $fillable = [
        'title',
        'sale_date',
        'applicable_categories',
        'applicable_shows',
        'min_price_range',
        'max_price_range',
        'discount_type',
        'discount_amount',
        'start_date',
        'end_date',
        'sale_date',
        'type',
        'status',
    ];
}
