<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ProductFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'product-facade'; // Binding name in the service container
    }
}