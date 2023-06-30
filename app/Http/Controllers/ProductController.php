<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Variation;
use App\Facades\ProductFacade;
class ProductController extends Controller
{
    // public function displayPhone()
    // {
    //     $products = Product::all();

    //     return view('products.productsClientPage', ['products' => $products]);

    //     //redirect the user and display success message

    // }

    public function index()
    {

        $products = Product::latest()->paginate(5);

        return view('products.showProduct', compact('products'));
    }

    public function create()
    {
        return view('products.createProduct');
    }


    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [ // Input Validator, $validator variable is used to validate the form data, and if validation fails, it redirects back to the form with the validation errors and retains the user's input.
                'productName' => 'required|string|max:255',
                'productDesc' => 'required|string',
                'productImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $image = $request->file('productImage');
            $imagePath = $image->storeAs('temp', uniqid() . '.' . $image->getClientOriginalExtension());

            $imageContents = file_get_contents(storage_path('app/' . $imagePath));

            $product = ProductFacade::createProduct([
                'productName' => $request->input('productName'),
                'productDesc' => $request->input('productDesc'),
                'productImage' => $imageContents,
                'variation' => [
                    'productStorage' => $request->input('productStorage'),
                    'productColor' => $request->input('productColor'),
                    'qty' => $request->input('qty'),
                    'productPrice' => $request->input('productPrice'),
                    'productStatus' => $request->input('productStatus'),
                ],
            ]);

            Storage::delete($imagePath);

            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            // Handle the exception by logging it or displaying an error message
            throw new \Exception('An error occurred while creating the product: ' . $e->getMessage());
        }
    }


    public function show($id)
    {
        $product = Product::find($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::find($id);
        return view('products.editProduct', compact('product'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [ // Input Validator, $validator variable is used to validate the form data, and if validation fails, it redirects back to the form with the validation errors and retains the user's input.
                'productName' => 'required|string|max:255',
                'productDesc' => 'required|string',
                'productImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $product = Product::find($id); // The $product instance is retrieved using Product::find($id) to access the existing product record from the database.

            $product->productName = $request->input('productName');
            $product->productDesc = $request->input('productDesc');

            if ($request->hasFile('productImage')) { // The $productImage property is only updated if $imageContents is set. This ensures that the image content is updated when a new image is uploaded, but it remains unchanged if no new image is provided in the request.
                $image = $request->file('productImage');
                $imagePath = $image->storeAs('temp', uniqid() . '.' . $image->getClientOriginalExtension());

                $imageContents = file_get_contents(storage_path('app/' . $imagePath)); // If a new product image is uploaded, the image is stored in a temporary location, and its contents are read using file_get_contents.

                $product->productImage = $imageContents;

                Storage::delete($imagePath);
            }

            ProductFacade::updateProduct($product, [
                'productName' => $request->input('productName'),
                'productDesc' => $request->input('productDesc'),
                'productImage' => isset($imageContents) ? $imageContents : null,
                'variation' => [
                    'productStorage' => $request->input('productStorage'),
                    'productColor' => $request->input('productColor'),
                    'qty' => $request->input('qty'),
                    'productPrice' => $request->input('productPrice'), 'productStatus' => $request->input('productStatus'),
                ],
            ]);
            return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            // Handle the exception by logging it or displaying an error message
            throw new \Exception('An error occurred while updating the product: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        Variation::where("productId", $id)->delete();
        Product::destroy($id);


        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
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
        
        return view('products.productsClientPage', compact('transformedVariations'));
        
        
    }

    public function productIndex()
    {
        $products = Product::all();

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><products></products>');

        foreach ($products as $product) {
            $recordXml = $xml->addChild('product');
            $recordXml->addChild('id', $product->id);
            $recordXml->addChild('productName', $product->productName);
            $recordXml->addChild('productDesc', $product->productDesc);
            $recordVariation = $recordXml->addChild('variation');
            // $recordVariation->addChild('productStorage', $product->productStorage);
            foreach ($product->variations as $variation) {
                $recordVariation->addChild('productStorage', $variation->productStorage);
                $recordVariation->addChild('productColor', $variation->productColor);
                $recordVariation->addChild('qty', $variation->qty);
                $recordVariation->addChild('productPrice', $variation->productPrice);
                $recordVariation->addChild('productStatus', $variation->productStatus);
            }
        }

        $xmlString = $xml->asXML();
        $xmlPath = public_path('xml/product.xml');
        file_put_contents($xmlPath, $xmlString);

        return $this->displayXml();
    }


    private function transformXML($xmlPath, $xslPath)
    {
        $xml = new \DOMDocument();
        $xml->load($xmlPath);

        $xsl = new \DOMDocument();
        $xsl->load($xslPath);

        $proc = new \XSLTProcessor();
        $proc->importStylesheet($xsl);

        // Set the CSRF token as a parameter
        $csrfToken = csrf_token();
        $proc->setParameter('', 'csrf_token', $csrfToken);

        return $proc->transformToXml($xml);
    }


    private function displayXml()
    {
        $xml = simplexml_load_file(public_path('xml/product.xml'));

        // Load the XSLT file
        $xsl = new \DOMDocument();
        $xsl->load(public_path('xml/product.xsl'));

        $processor = new \XSLTProcessor();
        $processor->importStylesheet($xsl);
        $html = $processor->transformToXml($xml);

        return view('products.productXml', ['html' => $html]);
    }

    public function getAllProducts()
    {
        $products = Product::all();

        $transformedProducts = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'productName' => $product->productName,
                'productDesc' => $product->productDesc,
                'variations' => $product->variations
               
            ];
        });
   
        return response()->json($transformedProducts);
    }

}