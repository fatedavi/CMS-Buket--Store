<?php

namespace App\Events;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public ChatMessage $message;

    public ChatConversation $conversation;

    public function __construct(ChatMessage $message, ChatConversation $conversation)
    {
        $this->message = $message;
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
            'message' => $this->message->toArray(),
            'conversation_id' => $this->conversation->id,
            'session_id' => $this->conversation->session_id,
        ];
    }
}
