<?php

namespace App\Http\Controllers;
use App\Events\CartProductDeleted;
use App\Events\CartProductEdited;
use App\Events\CartProductAdded;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use DOMDocument;
use Validator;
use XSLTProcessor;
use Storage;
use Auth;

use App\Models\Cart_product;
use App\Models\User;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Variation;
use Illuminate\Http\Request;



class CartController extends Controller
{

    //display current logged in user Cart
    public function index()
    {

        $user = Auth::user();
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
        
            
        return view('carts.viewCartProduct', compact('transformedCartProducts'));
    }


    public function addToCart(Request $request)
    {
        // Get the product and variation IDs from the request
        $productId = $request->input('productId');
        $variationId = $request->input('variationId');
        
        
        //Uncomment when merge
        // // Get the authenticated user, if available
        $user = Auth::user();

        //Temporary. Remove when merge-----
        // $user = User::first();
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
                $cart->cartTotalAmount = 0;
                
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
        
        // Log::info('AddToCart Controller before save same cartProduct');
        event(new CartProductAdded($cart->id, $tempAmount));
        
        // Redirect to the cart or any other desired page
        return redirect()->route('export.cart.products');
    }



    public function editCartQty(Request $request)
    {
        //Uncomment when merge
        // // Retrieve the authenticated user
        $user = Auth::user();
    
        // // Check if the user is logged in
        if (!$user) {
            // Handle the case where the user is not authenticated
            // Redirect to the login page or perform other actions as needed
            return redirect()->route('login.show');
        }
    
        $variationId = $request->input('variationId');
        $quantity = $request->input('quantity');
    
        // Define the validation rules
        $rules = [
            'quantity' => 'required|integer|min:1',
        ];
    
        // Define the custom error messages
        $messages = [
            'quantity.required' => 'The quantity is required.',
            'quantity.integer' => 'The quantity must be an integer.',
            'quantity.min' => 'The quantity must be at least :min.',
        ];
    
        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            // If validation fails, redirect back with the validation errors
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        //Uncomment when merge
        // // Retrieve the cart product based on the provided variationId and user
        // $cartProduct = Cart_product::where('variationId', $variationId)
        // ->where('userId', Auth::user()->id)
        // ->first();

        
        $cartProduct = Cart_product::where('variationId', $variationId)->first();
        
        
    
        if ($cartProduct) {
            // Retrieve the variation based on the variationId
            $variation = Variation::find($variationId);
    
            if ($variation) {
                // Check if the requested quantity exceeds the available quantity in the variation
                if ($quantity > $variation->qty) {
                    // Add a custom error message for the quantity field
                    $validator->errors()->add('quantity', 'The requested quantity exceeds the available quantity.');
                    return redirect()->back()->withErrors($validator)->withInput();
                }
    
                // Update the quantity and subtotal of the cart product
                $cartProduct->cartProductQty = $quantity;
                $cartProduct->subTotal = $variation->productPrice * $quantity;

                $cartProduct->save();

                event(new CartProductEdited($cartProduct->cartId));
            }
        }
    
        // Redirect back to the cart view or perform other actions as needed
        return redirect()->back();
    }

    public function deleteProduct($variationId)
    {
        // Find the cart product based on the provided variationId
        $cartProduct = Cart_product::where('variationId', $variationId)->first();
    
        if ($cartProduct) {
            // Dispatch the event
            
            event(new CartProductDeleted($cartProduct->id, $cartProduct->subTotal));

            // Delete the cart product
            $cartProduct->delete();
    
            
 
            // Return a JSON response indicating success
            return response()->json(['message' => 'Product successfully deleted from cart']);
        }
    
        // Return a JSON response indicating the cart product was not found
        return response()->json(['message' => 'Cart product not found'], 404);
    }
    
    


    public function viewCartProductsXml() {
    $xmlPath = storage_path('app/cartProducts.xml');
    $xslPath = storage_path('app/cartProducts.xslt');
    $dtdPath = storage_path('app/cartProducts.dtd');

    // Load the XML, XSLT, and DTD files
    $xml = new DOMDocument();
    $xml->load($xmlPath);
    $xml->validateOnParse = true;

    $xsl = new DOMDocument();
    $xsl->load($xslPath);

    //Not working when loaded into DOM
    // $dtd = new DOMDocument();
    // $dtd->load($dtdPath);
    // $dtd->validate();

    // Create the XSLT processor and import the stylesheet
    $processor = new XSLTProcessor();
    $processor->importStylesheet($xsl);

    // Apply the transformation to the XML and output the result
    $transformedXml = $processor->transformToXML($xml);
    echo $transformedXml;
}
                
public function clearCart()
{
    Cart_product::truncate();
    
    // Additional logic if needed
    
    return response()->json(['message' => 'Cart cleared successfully']);
}


    //Display mode before checkout. Not in edit
    public function exportCartProducts()
    {
        $user = auth()->user();

        if ($user->userType === 'staff') {
            return back()->with('error', 'Only customer allowed to add to cart');
        }

        // Retrieve the user's cart with associated cart products
        $cart = $user->cart;

        if (empty($cart) || is_null($cart)) {
            
            return back()->with('error', 'Your Cart is empty');
        }
        
        $cartProducts = $cart->cartProducts;
        
        if (empty($cartProducts) || is_null($cartProducts)) {
            return back()->with('error', 'Your Cart is empty');
        }

    
        // Define the XML structure
        $xmlData = new DOMDocument('1.0', 'UTF-8');
        $xmlData->formatOutput = true;
        $cartProductsNode = $xmlData->createElement('cartProducts');
        $xmlData->appendChild($cartProductsNode);

        foreach ($cartProducts as $cartProduct) {

            $variationId = $cartProduct->variationId;
            $variation = Variation::where('id', $variationId)->first();

            $productId = $variation->productId;
            $product = Product::where('id', $productId)->first();


            $productData = [
                'productName' => $product->productName,
                'productImage' => base64_encode($product->productImage),
                'productStorage' => $variation->productStorage,
                'productColor' => $variation->productColor,
                'cartProductQty' => $cartProduct->cartProductQty,
                'subTotal' => $cartProduct->subTotal
            ];

            $xmlCartProduct = $xmlData->createElement('cartProduct');
            array_walk_recursive($productData, function ($value, $key) use ($xmlData, $xmlCartProduct) {
                $xmlCartProduct->appendChild($xmlData->createElement($key, $value));
            });
            $cartProductsNode->appendChild($xmlCartProduct);
        }

        // Save the XML data to a file
        $xmlDataString = $xmlData->saveXML();
        Storage::disk('local')->put('cartProducts.xml', $xmlDataString);


        // Define the DTD structure
        $dtd = '<!DOCTYPE cartProducts [
  <!ELEMENT cartProducts (cartProduct*)>
  <!ELEMENT cartProduct (productName, productImage, productStorage, productColor, cartProductQty, subTotal)>
  <!ELEMENT productName (#PCDATA)>
  <!ELEMENT productImage (#PCDATA)>
  <!ELEMENT productStorage (#PCDATA)>
  <!ELEMENT productColor (#PCDATA)>
  <!ELEMENT cartProductQty (#PCDATA)>
  <!ELEMENT subTotal (#PCDATA)>
  <!ATTLIST cartProduct id CDATA #REQUIRED>
]>

        ';
    
        // Generate the DTD file and save it to storage
        $dtdFilePath = 'cartProducts.dtd';
        Storage::put($dtdFilePath, $dtd);
        

        // Generate the XSLT file and save it to storage
        $xslt ='
        <xsl:stylesheet version="1.0"
        xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
        <xsl:template match="/">
            <html>
                <head>
                    <meta charset="UTF-8"/>
                    <title>Cart</title>
                    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
                    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
                </head>
                <body>
                <header>
                <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                    <a class="navbar-brand" href="#">Mobile Website</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="#" onclick="window.location.href = \'' . route('display.variations') . '\'">Home</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
                    <div class="container">
                        <h1>Cart</h1>
                        <div class="d-flex justify-content-between mb-3">
                        <button type="button" class="btn btn-primary" onclick="window.location.href = \'' . route('view.cart') . '\'">Edit Cart</button>

                        <button type="button" class="btn btn-success" onclick="window.location.href = \'' . route('index.checkout') . '\'">Proceed to checkout</button>
                        </div>
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Storage</th>
                                    <th scope="col">Color</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <xsl:for-each select="cartProducts/cartProduct">
                                    <tr>
                                        <td>
                                            <xsl:value-of select="productName"/>
                                        </td>
                                        <td>
                                            <img src="data:image/jpeg;base64,{productImage}" width="200" height="200"/>
                                        </td>
                                        <td>
                                            <xsl:value-of select="productStorage"/>
                                        </td>
                                        <td>
                                            <xsl:value-of select="productColor"/>
                                        </td>
                                        <td>
                                            <input type="number" value="{cartProductQty}" style="width:40px" readonly="readonly"></input>
                                        </td>
                                        <td>
                                            <xsl:value-of select="subTotal"/>
                                        </td>
                                    </tr>
                                </xsl:for-each>
                                <tr>
                                    <th colspan="5">Total Amount:</th>
                                    <td>
                                        <xsl:value-of select="sum(cartProducts/cartProduct/subTotal)"/>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
    
                    <script>
                        function editCart() {
                            // Handle the logic for redirecting to the edit route
                            window.location.href = "/edit-cart";
                        }

                        function checkout() {
                            // Handle the logic for redirecting to the edit route
                            window.location.href = "/xxxxx";
                        }

                        
                    </script>
                </body>
            </html>
        </xsl:template>
    </xsl:stylesheet>
    
    
';
        $xsltFilePath = 'cartProducts.xslt';
        Storage::put($xsltFilePath, $xslt);
        $this->viewCartProductsXml();
        }


        


}