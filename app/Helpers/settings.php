<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

function setting(string $key, mixed $default = null): mixed
{
    return Cache::remember('setting.'.$key, 3600, function () use ($key, $default) {
        return Setting::where('key', $key)->value('value') ?? $default;
    });
}
