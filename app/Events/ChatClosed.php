<?php

namespace App\Events;

use App\Models\ChatConversation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class ChatClosed implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public ChatConversation $conversation;

    public function __construct(ChatConversation $conversation)
    {
        $this->conversation = $conversation;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.'.$this->conversation->session_id),
            new PrivateChannel('admin.chat'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversation->id,
            'session_id' => $this->conversation->session_id,
        ];
    }
}
