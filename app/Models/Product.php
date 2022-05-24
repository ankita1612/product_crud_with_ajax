<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  
class Product extends Model
{
    use HasFactory;
  
    protected $fillable = [
            'product_name','product_price','product_desc'
    ];

    public function product_images()
    {
        return $this->hasMany('App\Models\ProductImage',"product_id");
    }
}