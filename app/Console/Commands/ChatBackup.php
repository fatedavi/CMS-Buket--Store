<?php

namespace App\Console\Commands;

use App\Models\ChatConversation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ChatBackup extends Command
{
    protected $signature = 'chat:backup {--prune : Hapus percakapan closed yang sudah di-backup > X hari (default dari setting)}';

    protected $description = 'Backup semua percakapan chat ke file JSON';

    public function handle()
    {
        $filename = 'backups/chat-'.now()->format('Y-m-d-His').'.json';

        $conversations = ChatConversation::with('messages')->get()->toArray();

        Storage::put($filename, json_encode($conversations, JSON_PRETTY_PRINT));

        $this->info('Chat backup berhasil: '.$filename);

        if ($this->option('prune')) {
            $pruneDays = (int) (setting('chat_prune_days', 30));
            $cutoff = now()->subDays($pruneDays);
            $deleted = ChatConversation::where('status', 'closed')
                ->where('updated_at', '<', $cutoff)
                ->delete();

            $this->info('Menghapus '.$deleted.' percakapan closed > '.$pruneDays.' hari.');
        }

        return Command::SUCCESS;
    }
}
