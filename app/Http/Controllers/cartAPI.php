<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Cart_product;
use App\Models\Product;
use App\Models\Variation;
use App\Models\User;
use App\Models\Cart;

use App\Events\CartProductAdded;
use App\Events\CartProductEdited;
class cartAPI extends Controller
{

    
    function list(){
        return Cart_product::all();
    }

    function listById($id){
        return Cart_product::find($id);
    }

    public function displayPhone()
    {
        
        $transformedVariations = [];

        $variations = Variation::all();
        
        foreach ($variations as $variation) {
            $product = Product::find($variation->productId);
        
            $transformedVariations[] = [
                'productId' => $product->id,
                'variationId' => $variation->id,
                'variationPrice' => $variation->productPrice,
                'productName' => $product->productName,
                'productImage' => base64_encode($product->productImage),
                'productStorage' => $variation->productStorage,
                'productColor' => $variation->productColor
            ];
        }
        
        return $transformedVariations;
        
        
    }


    public function editCartView()
    {

        //Temporary
        $user = User::first();

        // Retrieve the user's cart with associated cart products
        $cart = $user->cart;
        $cartProducts = $cart->cartProducts;


        $transformedCartProducts = []; // Create an empty array to store the transformed cart products

        foreach ($cartProducts as $cartProduct) {
            $variation = Variation::where('id', $cartProduct->variationId)->first();
            $product = Product::where('id', $variation->productId)->first();
        
            $transformedCartProducts[] = [
                'productId' => $product->id,
                'variationId' => $variation->id,
                'variationPrice' => $variation->productPrice,
                'productName' => $product->productName,
                'productImage' => base64_encode($product->productImage),
                'productStorage' => $variation->productStorage,
                'productColor' => $variation->productColor,
                'cartProductQty' => $cartProduct->cartProductQty,
                'subTotal' => $cartProduct->subTotal
            ];
        }
        
        // Log::info($transformedCartProducts);
        return $transformedCartProducts;
    }

    public function displayCartProduct(){
        Log:info('DisplayCartProduct Server');
        
        // $user = auth()->user();
        $user = User::first();

        // Retrieve the user's cart with associated cart products
        $cart = $user->cart;
        
        $cartProducts = $cart->cartProducts;
        $productData = [];
        
        foreach ($cartProducts as $cartProduct) {
            

            $variationId = $cartProduct->variationId;
            $variation = Variation::where('id', $variationId)->first();

            $productId = $variation->productId;
            $product = Product::where('id', $productId)->first();


            $productData[] = [
                'productName' => $product->productName,
                'productImage' => base64_encode($product->productImage),
                'productStorage' => $variation->productStorage,
                'productColor' => $variation->productColor,
                'cartProductQty' => $cartProduct->cartProductQty,
                'subTotal' => $cartProduct->subTotal
            ];
        
        Log::info('Before return data from server');}
        Log::info($productData);
        // return $productData;
        return response()->json($productData);
    }

    //Get from client
    public function displayEditCart(Request $request){
        $variationId = $request->input('variationId');
        $quantity = $request->input('quantity');

        //Uncomment when merge web service
        // // Retrieve the cart product based on the provided variationId and user
        // $cartProduct = Cart_product::where('variationId', $variationId)
        // ->where('userId', Auth::user()->id)
        // ->first();
        $cartProduct = Cart_product::where('variationId', $variationId)->first();
        if ($cartProduct) {
            // Retrieve the variation based on the variationId
            $variation = Variation::find($variationId);
    
            if ($variation) {
                //Update the quantity and subtotal of the cart product
                $cartProduct->cartProductQty = $quantity;
                $cartProduct->subTotal = $variation->productPrice * $quantity;

                $cartProduct->save();

                event(new CartProductEdited($cartProduct->cartId));
            }
        }
        $this->editCartView();  
    }



    public function addToCart(Request $request){
        $variationId = $request->input('variationId');


         //Uncomment when merge
        // // Get the authenticated user, if available
        // $user = Auth::user();


        //Temporary. Remove when merge-----
        $user = User::first();
        //---------------------------------


        // Retrieve the user's cart with associated cart products
        $cart = $user->cart;
        // $cartProducts = $cart->cartProducts;

    
        // Check if the user has an existing cart
        if ($user) {
            $cart = $user->cart;
            
        } else {
            $cart = null;
        }
    
        // Create a new cart if none exists
        if (!$cart) {
            $cart = new Cart();
            if ($user) {
                $cart->userId = $user->id;
                
            } else {
                $cart->userId = null;
            }
            // Set any other relevant cart properties
            $cart->save();
        }
    
        // Check if there is an existing CartProduct with the same variation in the cart
        $existingCartProduct = Cart_product::where('cartId', $cart->id)
            ->where('variationId', $variationId)
            ->first();
        // dd($existingCartProduct->id);


        if ($existingCartProduct) {
            // Increase the quantity and update the subtotal of the existing CartProduct
            $existingCartProduct->cartProductQty += 1;
            $existingCartProduct->subTotal += Variation::find($variationId)->productPrice;
            
            $existingCartProduct->save();
        } else {
            // Create a new CartProduct and add it to the cart
            $cartProduct = new Cart_product();
            $cartProduct->cartId = $cart->id;
            
            $cartProduct->variationId = $variationId;
            $cartProduct->cartProductQty = 1;
            $cartProduct->subTotal =  Variation::find($variationId)->productPrice;
            // Set any other relevant cart product properties
            
            $cartProduct->save();

        }

        $tempAmount = Variation::find($variationId)->productPrice;
        
        event(new CartProductAdded($cart->id, $tempAmount));
        
        //Return here----------------------------------------
        $cartProducts = $cart->cartProducts;
        $productData = [];
        
        foreach ($cartProducts as $cartProduct) {
            

            $variationId = $cartProduct->variationId;
            $variation = Variation::where('id', $variationId)->first();

            $productId = $variation->productId;
            $product = Product::where('id', $productId)->first();


            $productData[] = [
                'productName' => $product->productName,
                'productImage' => base64_encode($product->productImage),
                'productStorage' => $variation->productStorage,
                'productColor' => $variation->productColor,
                'cartProductQty' => $cartProduct->cartProductQty,
                'subTotal' => $cartProduct->subTotal
            ];
        
        }
        
        // return $productData;
        return $productData;
        
    }

    


}
