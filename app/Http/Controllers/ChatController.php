<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Helpers\ImageHelper;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class ChatController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        $user = auth()->user();
        $maxMessage = $user ? 5000 : 1000;

        $sessionId = $request->input('session_id', session()->getId());
        $ipKey = 'chat-ip:'.$request->ip();

        if ($user) {
            $key = 'chat-user:'.$user->id;
            $maxAttempts = 20;
        } else {
            $key = 'chat-guest:'.$sessionId;
            $maxAttempts = 5;
        }

        if (RateLimiter::tooManyAttempts($key, $maxAttempts) || RateLimiter::tooManyAttempts($ipKey, $maxAttempts)) {
            $seconds = min(
                RateLimiter::availableIn($key),
                RateLimiter::availableIn($ipKey)
            );

            return response()->json([
                'success' => false,
                'message' => "Terlalu banyak pesan. Coba lagi dalam {$seconds} detik.",
            ], 429);
        }

        RateLimiter::hit($key, 60);
        RateLimiter::hit($ipKey, 60);

        $rules = ['message' => 'required|string|max:'.$maxMessage];

        if ($user) {
            $rules['image'] = 'nullable|image';
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
            ImageHelper::compress($imagePath, maxSizeKB: 2048, maxDimension: 1200);
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

        try {
            broadcast(new MessageSent($msg, $conversation));
        } catch (BroadcastException $e) {
            // Reverb tidak jalan — chat tetap berfungsi tanpa real-time
        }

        return response()->json([
            'success' => true,
            'message' => $msg,
            'conversation_id' => $conversation->id,
            'session_id' => $sessionId,
            'user' => $user ? ['name' => $user->name, 'is_admin' => $user->is_admin] : null,
        ]);
    }

    public function adminStatus(): JsonResponse
    {
        $lastSeen = Cache::get('admin_online_at');
        $online = $lastSeen && $lastSeen->gt(now()->subMinute());

        return response()->json([
            'online' => $online,
            'last_seen_ago' => $lastSeen ? $lastSeen->diffForHumans() : null,
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
