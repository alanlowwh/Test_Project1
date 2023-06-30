<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\CartProductDeleted;
use App\Events\CartProductEdited;
use App\Events\CartProductAdded;
use App\Observers\CartObserveTotal;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{


    public function boot()
    {
        parent::boot();

        Event::listen(CartProductDeleted::class, CartObserveTotal::class . '@handleCartProductDeleted');
        Event::listen(CartProductEdited::class, CartObserveTotal::class . '@handleCartProductEdited');
        Event::listen(CartProductAdded::class, CartObserveTotal::class . '@handleCartProductAdded');

        Log::info('EventServiceProvider booted');
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
