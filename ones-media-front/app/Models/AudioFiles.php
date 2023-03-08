<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudioFiles extends Model
{
    use HasFactory;
    protected $fillable = [
        'shows_id',
        'file_original_name',
        'file_name'
    ];
    public function show()
    {
        return $this->belongsTo(Shows::class, 'shows_id');
    }
}