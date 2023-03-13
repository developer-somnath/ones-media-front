<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleFiles extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'image',
        'description',
        'description',
        'file_original_name',
        'file_name',
        'status',
    ];
}
