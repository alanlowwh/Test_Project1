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


class CartProductAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cartId;
    public $tempAmount;

    public function __construct($cartId, $tempAmount)
    {
        // Log::info('Add constructor');

        $this->cartId = $cartId;
        $this->tempAmount = $tempAmount;
        Log::info($this->cartId);
        Log::info($this->tempAmount);
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
