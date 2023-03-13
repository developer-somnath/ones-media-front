<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_type',
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
        'email_verified_at',
        'status',
        'password',
        
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function carts(){
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }
    public function country()
    {
        return $this->belongsTo(Countries::class, 'country_id');
    }
    public function state()
    {
        return $this->belongsTo(States::class, 'state_id');
    }

    public function Wishlists(){
        return $this->hasMany(Wishlist::class);
    }

}
