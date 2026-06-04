<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackAdminActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        Cache::put('admin_online_at', now(), 60);

        return $next($request);
    }
}
