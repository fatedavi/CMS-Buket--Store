<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('admin.chat', function ($user) {
    return (bool) ($user->is_admin ?? false);
});

Broadcast::channel('chat.{sessionId}', function ($user, $sessionId) {
    if ($user && $user->is_admin) {
        return true;
    }

    $requestSessionId = request()->header('X-Session-Id', request()->input('session_id'));

    return $requestSessionId === $sessionId;
});
