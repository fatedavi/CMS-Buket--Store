<?php

namespace App\Console\Commands;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Console\Command;

class ChatAutoClose extends Command
{
    protected $signature = 'chat:auto-close {--hours=3 : Jumlah jam tanpa balasan sebelum ditutup}';

    protected $description = 'Tutup otomatis percakapan yang tidak dibalas customer dalam X jam';

    public function handle(): int
    {
        $hours = (int) $this->option('hours');
        $cutoff = now()->subHours($hours);
        $closed = 0;

        ChatConversation::where('status', 'active')
            ->chunk(50, function ($conversations) use ($cutoff, &$closed) {
                foreach ($conversations as $conv) {
                    $lastMsg = $conv->messages()->latest()->first();

                    if ($lastMsg && $lastMsg->sender === 'admin' && $lastMsg->created_at->lt($cutoff)) {
                        ChatMessage::create([
                            'chat_conversation_id' => $conv->id,
                            'sender' => 'system',
                            'message' => 'Percakapan ditutup otomatis karena tidak ada balasan.',
                        ]);

                        $conv->update(['status' => 'closed']);
                        $closed++;
                    }
                }
            });

        $this->info("{$closed} percakapan ditutup otomatis.");

        return self::SUCCESS;
    }
}
