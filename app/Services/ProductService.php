<?php
namespace App\Services;

use App\Models\Product;
use App\Models\Variation;

class ProductService
{
    public function createProduct(array $data)
    {
        // Create a new product instance and save it to the database
        $product = new Product($data);
        $product->save();

        // Create a new variation for the product
        $variation = new Variation($data['variation']);
        $product->variations()->save($variation);

        return $product;
    }

    public function updateProduct(Product $product, array $data)
    {
        $product->update($data);

        $variation = $product->variations()->first();
        $variation->update($data['variation']);

        return $product;
    }

    public function deleteProduct(Product $product)
    {
        Variation::where('productId', $product->id)->delete();
        $product->delete();
    }
}