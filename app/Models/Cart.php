<?php

namespace App\Models;

// use App\Observers\CartObserver;
use App\Observers\CartObserveTotal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'Cart';
    protected $fillable = [
        'userId',
        'cartTotalAmount',
    ];

    protected static function boot()
    {
        parent::boot();

        self::observe(CartObserveTotal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function cartProducts()
    {
        return $this->hasMany(Cart_product::class, 'cartId');
    }
}
