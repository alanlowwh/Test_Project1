<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;


class CartProductDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cartProductId;
    public $subTotal;

    public function __construct($cartProductId, $subTotal)
    {
        // Log::info($cartProductId, $subTotal);

        $this->cartProductId = $cartProductId;
        $this->subTotal = $subTotal;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
