<?php

namespace App\Observers;
use App\Events\CartProductDeleted;
use App\Events\CartProductEdited;
use App\Events\CartProductAdded;

use App\Models\Cart;

interface CartObserver
{

    public function handleCartProductDeleted(CartProductDeleted $event);

    public function handleCartProductEdited(CartProductEdited $event);

    public function handleCartProductAdded(CartProductAdded $event);
    // Add more methods as needed
}
