<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\Channel;

class NewMessage2
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public $userId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data = [], $userId)
    {
        $this->data = is_array($data) ? $data : [];
        $this->userId = $userId;
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
