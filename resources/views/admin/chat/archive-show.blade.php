@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('admin.chat.archive') }}" class="flex items-center gap-1 text-sm text-warm-gray hover:text-dark-oak transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke arsip
        </a>
        <span class="text-xs bg-gray-100 text-gray-500 rounded-full px-3 py-1">Ditutup</span>
    </div>

    <div class="bg-white rounded-2xl border border-amber-100 overflow-hidden shadow-sm">
        <div class="bg-cream px-4 py-3 border-b border-amber-100 flex items-center gap-3">
            @php
                $initial = strtoupper(substr($conversation->customer_name ?? 'P', 0, 1));
                $color = 'bg-gray-400';
            @endphp
            <div class="w-9 h-9 rounded-full {{ $color }} text-white flex items-center justify-center text-sm font-semibold">{{ $initial }}</div>
            <div>
                <p class="text-sm font-medium text-dark-oak">{{ $conversation->customer_name ?? 'Pengunjung' }}</p>
                <p class="text-[11px] text-warm-gray">{{ $conversation->created_at->format('j F Y H:i') }}</p>
            </div>
        </div>

        <div class="p-4 space-y-3 max-h-[500px] overflow-y-auto" id="chat-messages"
             x-data
             x-init="$nextTick(() => { const el = $el; el.scrollTop = el.scrollHeight; })">
            @forelse($conversation->messages as $msg)
            <div class="flex {{ $msg->sender === 'admin' ? 'justify-end' : 'justify-start' }}">
                @php $isAdmin = $msg->sender === 'admin'; @endphp
                <div class="max-w-[80%] rounded-2xl px-4 py-2.5 text-sm leading-relaxed {{ $isAdmin ? 'bg-sage-green text-white rounded-tr-md' : 'bg-gray-100 text-dark-oak rounded-tl-md' }}">
                    @php
                        $text = e($msg->message);
                        $text = preg_replace('/\[gambar\](.*?)\[\/gambar\]/', '<img src="/storage/$1" class="max-w-full rounded-lg my-1" alt="gambar">', $text);
                    @endphp
                    {!! nl2br($text) !!}
                    <p class="text-[10px] mt-1.5 {{ $isAdmin ? 'text-white/70' : 'text-warm-gray' }}" style="text-align: right;">{{ $msg->created_at->format('H:i') }}</p>
                </div>
            </div>
            @empty
            <p class="text-center text-warm-gray text-sm py-8">Belum ada pesan.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
