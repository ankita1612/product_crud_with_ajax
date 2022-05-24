<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
     protected $fillable = [
            'image'
    ];
    use HasFactory;
    public function product()
    {
        return $this->belongsTo('App\Models\Product',"product_id");
    }
}
