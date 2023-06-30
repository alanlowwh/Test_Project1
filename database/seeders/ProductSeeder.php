<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product1 = new Product([
            'productName' => 'Product A',
            'productPrice' => 2999.99,
            'productDesc' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'productStatus' => 'available'
        ]);

        $product2 = new Product([
            'productName' => 'Product B',
            'productPrice' => 1999.99,
            'productDesc' => 'Praesent luctus euismod massa, quis efficitur enim tincidunt ut.',
            'productStatus' => 'out of stock'
        ]);

        $product3 = new Product([
            'productName' => 'Product C',
            'productPrice' => 2499.99,
            'productDesc' => 'Nullam a risus eu augue pharetra finibus.',
            'productStatus' => 'available'
        ]);

        $product1->save();
        $product2->save();
        $product3->save();
    }
}
