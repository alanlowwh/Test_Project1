<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;
    protected $table = 'Variation';
    protected $fillable = [
        'productId',
        'productStorage',
        'productColor',
        'qty',
        'productPrice',
        'productStatus',
    ];

    // public function product()
    // {
    //     return $this->hasOne(Product::class, 'id', 'productId');
        
    // }

    public function products()
    {
        return $this->belongsTo(Product::class, 'id', 'productId');
    }

    public function cart_product()
    {
        return $this->hasMany(CartProduct::class);
    }

    public function order_product()
    {
        return $this->hasMany(OrderProduct::class);
    }

    
}