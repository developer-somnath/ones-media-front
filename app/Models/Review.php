<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'show_id',
        'name',
        'email',
        'rating',
        'description',
        'is_approved',
        'status'
    ];
}
