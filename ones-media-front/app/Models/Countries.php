<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    use HasFactory;

    public function states()
    {
        return $this->hasMany(States::class, 'country_id');
    }

    public function user()
    {
        return $this->hasMany(User::class, 'user_id');
    }
}
