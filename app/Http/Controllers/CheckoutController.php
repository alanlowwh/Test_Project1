<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Cart_product;
use App\Models\Variation;
use App\Models\Product;
use App\Models\User;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartDetails = Cart::where('userId', Auth::id())->get();
        $cartitems = Cart_product::where('cartId', $cartDetails->first()->id)->get();
        return view('checkout', compact('cartitems'));
    }

    public function confirmOrder(Request $request)
    {
        $request->validate([
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email',
            'phone_number' => 'required|numeric|digits_between:10,11',
            'address' => 'required',
            'city' => 'required',
            'postal_code' => 'required|numeric|digits:5',
        ]);

        $cartDetails = Cart::where('userId', Auth::id())->get();

        $order = new Order();
        $order->user_id = Auth::id();
        $order->name = $request->input('name');
        $order->email = $request->input('email');
        $order->phone_number = $request->input('phone_number');
        $order->address = $request->input('address');
        $order->city = $request->input('city');
        $order->postal_code = $request->input('postal_code');
        //calculate total price from cart 
        $total = 0;
        $cartDetails = Cart::where('userId', Auth::id())->get();
        $cartitems_total = Cart_product::where('cartId', $cartDetails->first()->id)->get();
        foreach($cartitems_total as $prod)
        {
            $total += $prod->subTotal;
        }
        $order->total_price = $total;

        $order->tracking_no = 'no'.rand(1111,9999);
        $order->save();

        $order->id;
        $cartDetails = Cart::where('userId', Auth::id())->get();
        $cartItems = Cart_product::where('cartId', $cartDetails->first()->id)->get();

        foreach ($cartItems as $cartItem) {
            $variation = Variation::where('id', $cartItem->variationId)->first();
            $product = Product::where('id', $variation->productId)->first();

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'variation_id' =>$cartItem->variationId,
                'quantity' => $cartItem->cartProductQty,
                'price' => $variation->productPrice,
        ]);
}

        //update user details during checkout
        $user = User::where('id', Auth::id())->first();
        $user->name = $request->input('name');
        $user->phoneNumber = $request->input('phone_number');
        $user->address = $request->input('address');
        $user->city = $request->input('city');
        $user->postcode = $request->input('postal_code');
        $user->update();


        //empty cart after succesful order
        // $cartitems = Cart::where('user_id', Auth::id())->get();
        // Cart::destroy($cartitems);

        return redirect('/')->with('status',"Order placed succesfully");

    }
}
