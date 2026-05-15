@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="font-playfair text-2xl text-dark-oak">Arsip Chat</h1>
    <span class="text-sm text-warm-gray">{{ $conversations->total() }} percakapan</span>
</div>

@if($conversations->count())
<div class="space-y-3">
    @foreach($conversations as $conv)
    @php
        $initial = strtoupper(substr($conv->customer_name ?? 'P', 0, 1));
        $colors = ['bg-sage-green', 'bg-terracotta', 'bg-[#c4956a]', 'bg-[#8b5e3c]', 'bg-[#a7c4a0]'];
        $color = $colors[$loop->index % count($colors)];
    @endphp
    <a href="{{ route('admin.chat.archive.show', $conv) }}"
       class="block bg-white rounded-2xl border border-amber-100 p-4 hover:shadow-md hover:border-amber-200 transition-all">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full {{ $color }} text-white/80 flex items-center justify-center text-sm font-semibold flex-shrink-0">
                {{ $initial }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <h3 class="font-medium text-sm text-dark-oak truncate">{{ $conv->customer_name ?? 'Pengunjung' }}</h3>
                    <span class="text-[11px] text-warm-gray flex-shrink-0">{{ $conv->created_at->diffForHumans() }}</span>
                </div>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-xs text-warm-gray truncate">{{ $conv->messages_count }} pesan</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                    <span class="text-[11px] text-gray-400">Ditutup</span>
                </div>
            </div>
            <svg class="w-4 h-4 text-sand flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
    </a>
    @endforeach
</div>
<div class="mt-6">
    {{ $conversations->links() }}
</div>
@else
<div class="bg-white rounded-2xl border border-amber-100 p-12 text-center">
    <div class="w-16 h-16 bg-cream rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-7 h-7 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
    </div>
    <p class="text-warm-gray text-sm">Belum ada arsip.</p>
</div>
@endif
@endsection
