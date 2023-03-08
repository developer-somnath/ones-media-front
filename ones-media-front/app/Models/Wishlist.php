<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    protected $table = "wishlists";
    protected $fillable = [
        'user_id',
        'item_id',
        'type',
        'status'
    ];

    /* public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    } */

    public function show(){
        return $this->belongsTo(Shows::class, 'item_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
