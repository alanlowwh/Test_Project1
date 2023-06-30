<?php

namespace App\Observers;


use App\Events\CartProductDeleted;
use App\Events\CartProductEdited;
use App\Events\CartProductAdded;
use App\Models\Cart_product;

use App\Models\Cart;
use Illuminate\Support\Facades\Log;

class CartObserveTotal implements CartObserver
{

    public function __invoke(){
        Log::info('Invoke method');

    }

    public function handleCartProductDeleted(CartProductDeleted $event)
    {

        Log::info('handleCart MEthod');
        Log::info('Cart Product ID: '.$event->cartProductId);
        Log::info('Subtotal: '.$event->subTotal);
        
        $cartProduct = Cart_product::find($event->cartProductId);
        Log::info($cartProduct);
        if ($cartProduct) {

            
            $cart = $cartProduct->cart;
            $cart->cartTotalAmount -= $event->subTotal;
            $cart->save();
        }
    }


    public function handleCartProductEdited(CartProductEdited $event){

        
        Log::info('Edit MEthod');
        // Log::info('Cart ID: '.$event->cartId);

        // Get all Cart_product records with the same cartId
        $cartProducts = Cart_product::where('cartId', $event->cartId)->get();

        // Calculate the total subTotal
        $totalSubTotal = 0;

        foreach ($cartProducts as $cartProduct) {
            $totalSubTotal += $cartProduct->subTotal;
        }


        // Update the cartTotalAmount in the Cart model
        $cart = Cart::find($event->cartId);
        if ($cart) {
            $cart->cartTotalAmount = $totalSubTotal;
            $cart->save();
        }

    }



    public function handleCartProductAdded(CartProductAdded $event)
    {

        
        $cart = Cart::find($event->cartId);
        if ($cart) {
            
            $cart->cartTotalAmount += $event->tempAmount;
            $cart->save();
            
        }

    }

    



}
