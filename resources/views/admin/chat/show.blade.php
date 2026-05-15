@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('admin.chat') }}" class="flex items-center gap-1 text-sm text-warm-gray hover:text-dark-oak transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        @if($conversation->status === 'active')
        <form action="{{ route('admin.chat.close', $conversation) }}" method="POST" onsubmit="return confirm('Tutup percakapan ini?')">
            @csrf
            <button type="submit" class="flex items-center gap-1.5 border border-terracotta text-terracotta rounded-xl px-3 py-1.5 text-xs hover:bg-terracotta hover:text-white transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Tutup Chat
            </button>
        </form>
        @else
        <span class="text-xs bg-gray-100 text-gray-500 rounded-full px-3 py-1">Ditutup</span>
        @endif
    </div>

    {{-- Chat Card --}}
    <div class="bg-white rounded-2xl border border-amber-100 overflow-hidden shadow-sm">
        {{-- Profile header --}}
        <div class="bg-cream px-4 py-3 border-b border-amber-100 flex items-center gap-3">
            @php
                $initial = strtoupper(substr($conversation->customer_name ?? 'P', 0, 1));
                $color = 'bg-sage-green';
            @endphp
            <div class="w-9 h-9 rounded-full {{ $color }} text-white flex items-center justify-center text-sm font-semibold">{{ $initial }}</div>
            <div>
                <p class="text-sm font-medium text-dark-oak">{{ $conversation->customer_name ?? 'Pengunjung' }}</p>
                <p class="text-[11px] text-warm-gray">{{ $conversation->created_at->format('j F Y H:i') }}</p>
            </div>
        </div>

        {{-- Messages --}}
        <div class="p-4 space-y-3 max-h-[460px] overflow-y-auto" id="chat-messages"
             x-data
             x-init="$nextTick(() => { const el = $el; el.scrollTop = el.scrollHeight; })">
            @forelse($conversation->messages as $msg)
            <div class="flex {{ $msg->sender === 'admin' ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[80%] rounded-2xl px-4 py-2.5 text-sm leading-relaxed {{ $msg->sender === 'admin' ? 'bg-sage-green text-white rounded-tr-md' : 'bg-gray-100 text-dark-oak rounded-tl-md' }}">
                    @php
                        $text = e($msg->message);
                        $text = preg_replace('/\[gambar\](.*?)\[\/gambar\]/', '<img src="/storage/$1" class="max-w-full rounded-lg my-1" alt="gambar">', $text);
                    @endphp
                    {!! nl2br($text) !!}
                    <p class="text-[10px] mt-1.5 {{ $msg->sender === 'admin' ? 'text-white/70' : 'text-warm-gray' }}" style="text-align: right;">{{ $msg->created_at->format('H:i') }}</p>
                </div>
            </div>
            @empty
            <p class="text-center text-warm-gray text-sm py-8">Belum ada pesan.</p>
            @endforelse
        </div>

        {{-- Reply input --}}
        @if($conversation->status === 'active')
        <form action="{{ route('admin.chat.reply', $conversation) }}" method="POST" class="border-t border-amber-100 p-3 bg-white">
            @csrf
            <div class="flex gap-2 items-end">
                <textarea name="message" rows="1" x-data
                          x-init="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
                          @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
                          class="flex-1 border border-sand rounded-xl px-4 py-2.5 text-sm focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20 resize-none max-h-32"
                          placeholder="Ketik balasan..." required></textarea>
                <button type="submit" class="bg-sage-green text-white rounded-xl px-5 py-2.5 text-sm font-medium hover:brightness-110 transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V5m0 0l-7 7m7-7l7 7"/></svg>
                    Kirim
                </button>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection
