<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatConversation extends Model
{
    protected $fillable = ['session_id', 'customer_name', 'status', 'admin_typing_at'];

    protected function casts(): array
    {
        return [
            'admin_typing_at' => 'datetime',
        ];
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }
}
