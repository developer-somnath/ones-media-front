<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeDownload extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'image',
        'description',
        'file_original_name',
        'file_name',
        'download_date',
        'status',
    ];
}
