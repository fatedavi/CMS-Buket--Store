<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_conversations', function (Blueprint $table) {
            $table->timestamp('admin_typing_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('chat_conversations', function (Blueprint $table) {
            $table->dropColumn('admin_typing_at');
        });
    }
};
