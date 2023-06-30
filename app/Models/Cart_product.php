<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart_product extends Model
{
    use HasFactory;
    protected $table = 'Cart_product';
    protected $fillable = [
        'cartId',
        'variationId',
        'cartProductQty',
        'subTotal',
    ];


// CartProduct.php
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cartId');
    }


    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }

}