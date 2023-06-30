<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';
    protected $fillable = [
        'order_id',
        'product_id',
        'variation_id',
        'price',
        'quantity',
    ];

    public function products(): BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id', 'id');
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(Variation::class,'variation_id', 'id');
    }
}

