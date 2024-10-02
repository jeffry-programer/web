<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\Channel;

class NewMessage2
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function broadcastOn()
    {
        return new Channel('chat-channel');
    }
  
    public function broadcastAs()
    {
        return 'new-message2';
    }
}
