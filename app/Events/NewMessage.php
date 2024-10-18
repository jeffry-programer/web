<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;

class NewMessage
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $conversation_id;
    public $userId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message, $conversation_id, $userId)
    {
        $this->message = $message;
        $this->conversation_id = $conversation_id;
        $this->userId = $userId;
    }

    public function broadcastOn()
    {
        return new Channel('chat-channel');
    }
  
    public function broadcastAs()
    {
        return 'new-message';
    }
}
