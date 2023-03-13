<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    protected $fillable = [
        'image',
        'short_description',
        'status'  
    ];
    /**
     * Get all of the shows for the Categories
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
}
