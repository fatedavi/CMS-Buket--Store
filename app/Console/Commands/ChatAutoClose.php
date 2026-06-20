<?php

namespace App\Console\Commands;

use App\Events\ChatClosed;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Console\Command;

class ChatAutoClose extends Command
{
    protected $signature = 'chat:auto-close {--hours= : Jumlah jam tanpa balasan sebelum ditutup (default dari setting)}';

    protected $description = 'Tutup otomatis percakapan yang tidak dibalas customer dalam X jam';

    public function handle(): int
    {
        $hours = (int) ($this->option('hours') ?: setting('chat_auto_close_hours', 3));
        $closed = $this->closeInactive($hours);

        $this->info("{$closed} percakapan ditutup otomatis.");

        return self::SUCCESS;
    }

    public function closeInactive(int $hours = 3): int
    {
        $cutoff = now()->subHours($hours);
        $closed = 0;

        ChatConversation::where('status', 'active')
            ->chunk(50, function ($conversations) use ($cutoff, &$closed) {
                foreach ($conversations as $conv) {
                    $lastMsg = $conv->messages()->latest()->first();

                    $shouldClose = false;

                    if ($lastMsg && $lastMsg->created_at->lt($cutoff)) {
                        $shouldClose = true;
                    }

                    if (! $lastMsg && $conv->created_at->lt($cutoff)) {
                        $shouldClose = true;
                    }

                    if ($shouldClose) {
                        ChatMessage::create([
                            'chat_conversation_id' => $conv->id,
                            'sender' => 'system',
                            'message' => 'Percakapan ditutup otomatis karena tidak ada balasan.',
                        ]);

                        $conv->update(['status' => 'closed']);
                        try {
                            broadcast(new ChatClosed($conv));
                        } catch (BroadcastException $e) {
                            // Reverb tidak jalan — chat tetap berfungsi
                        }
                        $closed++;
                    }
                }
            });

        return $closed;
    }
}
