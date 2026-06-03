<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ChatController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        $user = auth()->user();
        $maxMessage = $user ? 5000 : 1000;

        $sessionId = $request->input('session_id', session()->getId());

        if (! $user) {
            $key = 'chat-guest:'.$sessionId;

            if (RateLimiter::tooManyAttempts($key, 5)) {
                $seconds = RateLimiter::availableIn($key);

                return response()->json([
                    'success' => false,
                    'message' => "Terlalu banyak pesan. Coba lagi dalam {$seconds} detik.",
                ], 429);
            }

            RateLimiter::hit($key, 60);
        }
        $rules = ['message' => 'required|string|max:'.$maxMessage];

        if ($user) {
            $rules['image'] = 'nullable|image|max:2048';
        }

        $data = $request->validate($rules);
        $customerName = $user ? $user->name : 'Pengunjung';

        $conversation = ChatConversation::firstOrCreate(
            ['session_id' => $sessionId],
            ['customer_name' => $customerName, 'status' => 'active']
        );

        if ($conversation->customer_name === 'Pengunjung' && $user) {
            $conversation->update(['customer_name' => $user->name]);
        }

        $imagePath = null;
        if ($user && $request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat', 'public');
        }

        $messageContent = $data['message'];
        if ($imagePath) {
            $messageContent .= "\n[gambar]".$imagePath.'[/gambar]';
        }

        $msg = ChatMessage::create([
            'chat_conversation_id' => $conversation->id,
            'sender' => 'customer',
            'message' => $messageContent,
        ]);

        broadcast(new MessageSent($msg, $conversation));

        return response()->json([
            'success' => true,
            'message' => $msg,
            'conversation_id' => $conversation->id,
            'session_id' => $sessionId,
            'user' => $user ? ['name' => $user->name, 'is_admin' => $user->is_admin] : null,
        ]);
    }

    public function messages(Request $request): JsonResponse
    {
        $sessionId = $request->get('session_id', session()->getId());
        $conversation = ChatConversation::where('session_id', $sessionId)->first();

        if (! $conversation) {
            return response()->json(['messages' => [], 'closed' => false]);
        }

        $conversation->load('messages');

        return response()->json([
            'messages' => $conversation->messages,
            'closed' => $conversation->status === 'closed',
            'user' => auth()->user() ? ['name' => auth()->user()->name, 'is_admin' => auth()->user()->is_admin] : null,
        ]);
    }
}
