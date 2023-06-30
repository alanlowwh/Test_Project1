<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class CartProductEdited
{
    use Dispatchable, SerializesModels;

    public $cartId;
    

    public function __construct($cartProductId)
    {

        $this->cartId = $cartProductId;
        
    }
}
