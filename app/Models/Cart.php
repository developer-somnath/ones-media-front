<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'item_id',
        'quantity',
        'price',
        'discount',
        'type',
        'product_type',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shows()
    {
        return $this->belongsTo(Shows::class, 'item_id');
    }
    public function sampleFiles()
    {
        return $this->belongsTo(SampleFiles::class, 'item_id');
    }
    /* public function scopeItem()
    {
        if ($this->product_type === 1) {
            return $this->shows();
        } else {
            return $this->sampleFiles();
        }
    } */

    public function scopeItem($query)
    {
        return $query
              ->when($this->product_type === 1,function($q){
                  return $q->with('shows');
             })
             ->when($this->product_type === 2,function($q){
                  return $q->with('sampleFiles');
             });
    }

}
