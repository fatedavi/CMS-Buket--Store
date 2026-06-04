@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="font-playfair text-2xl text-dark-oak">Percakapan</h1>
    <span class="text-sm text-warm-gray">{{ $conversations->total() }} total</span>
</div>

@if($conversations->count())
<div class="space-y-3">
    @foreach($conversations as $conv)
    @php
        $initial = strtoupper(substr($conv->customer_name ?? 'P', 0, 1));
        $colors = ['bg-sage-green', 'bg-terracotta', 'bg-[#c4956a]', 'bg-[#8b5e3c]', 'bg-[#a7c4a0]'];
        $color = $colors[$loop->index % count($colors)];
    @endphp
    <a href="{{ route('admin.chat.show', $conv) }}"
       class="block bg-white rounded-2xl border border-amber-100 p-4 hover:shadow-md hover:border-amber-200 transition-all">
        <div class="flex items-center gap-3">
            {{-- Avatar --}}
            <div class="w-10 h-10 rounded-full {{ $color }} text-white flex items-center justify-center text-sm font-semibold flex-shrink-0">
                {{ $initial }}
            </div>
            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <h3 class="font-medium text-sm text-dark-oak truncate">{{ $conv->customer_name ?? 'Pengunjung' }}</h3>
                    <span class="text-[11px] text-warm-gray flex-shrink-0">{{ $conv->created_at->diffForHumans() }}</span>
                </div>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-xs text-warm-gray truncate">{{ $conv->messages_count }} pesan</span>
                    <span class="w-1.5 h-1.5 rounded-full {{ $conv->status === 'active' ? 'bg-sage-green' : 'bg-gray-300' }}"></span>
                    <span class="text-[11px] {{ $conv->status === 'active' ? 'text-sage-green' : 'text-gray-400' }}">{{ $conv->status === 'active' ? 'Aktif' : 'Ditutup' }}</span>
                </div>
            </div>
            {{-- Arrow --}}
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
        <svg class="w-7 h-7 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
    </div>
    <p class="text-warm-gray text-sm">Belum ada percakapan.</p>
</div>
@endif
@endsection

@push('scripts')
<script>
(function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
                   || document.querySelector('input[name="_token"]')?.value
                   || '';

    // ── Notifikasi pesan baru ───────────────────────────────────────────────
    let newMsgCount = 0;
    const body = document.body;

    function showNotification(count) {
        // Hapus badge lama jika ada
        const oldBadge = document.getElementById('new-msg-badge');
        if (oldBadge) oldBadge.remove();

        if (count <= 0) return;

        const badge = document.createElement('div');
        badge.id = 'new-msg-badge';
        badge.className = 'fixed bottom-6 right-6 z-50 bg-terracotta text-white text-sm px-4 py-3 rounded-2xl shadow-lg flex items-center gap-2 cursor-pointer hover:brightness-110 transition-all';
        badge.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg> ' + count + ' pesan baru — klik untuk muat ulang';
        badge.addEventListener('click', function () { location.reload(); });
        body.appendChild(badge);

        // Auto-reload setelah 5 detik
        setTimeout(function () { location.reload(); }, 5000);
    }

    // ── WebSocket / Echo ────────────────────────────────────────────────────
    if (window.Echo) {
        window.Echo.private('admin.chat')
            .listen('MessageSent', function (e) {
                console.log('[Admin Index] MessageSent via admin.chat:', e);
                if (e.message && e.message.sender === 'customer') {
                    newMsgCount++;
                    showNotification(newMsgCount);
                }
            })
            .listen('ChatClosed', function (e) {
                console.log('[Admin Index] ChatClosed:', e);
                location.reload();
            });
    }

    // ── Polling fallback: reload tiap 30 detik kalau Echo mati ─────────────
    if (!window.Echo) {
        setTimeout(function () { location.reload(); }, 30000);
    }
})();
</script>
@endpush
