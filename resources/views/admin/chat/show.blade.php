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
            @endphp
            <div class="w-9 h-9 rounded-full bg-sage-green text-white flex items-center justify-center text-sm font-semibold">{{ $initial }}</div>
            <div>
                <p class="text-sm font-medium text-dark-oak">{{ $conversation->customer_name ?? 'Pengunjung' }}</p>
                <p class="text-[11px] text-warm-gray">{{ $conversation->created_at->format('j F Y H:i') }}</p>
            </div>
            {{-- Status WebSocket --}}
            <span id="ws-status" class="ml-auto text-[10px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-400">Menghubungkan...</span>
        </div>

        {{-- Messages --}}
        <div class="p-4 space-y-3 max-h-[460px] overflow-y-auto" id="chat-messages">
            @forelse($conversation->messages as $msg)
            <div class="flex {{ $msg->sender === 'admin' ? 'justify-end' : 'justify-start' }}" id="msg-{{ $msg->id }}">
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
            <p class="text-center text-warm-gray text-sm py-8" id="no-messages">Belum ada pesan.</p>
            @endforelse
        </div>

        {{-- Reply input — kirim via AJAX bukan form submit --}}
        @if($conversation->status === 'active')
        <form id="reply-form" action="{{ route('admin.chat.reply', $conversation) }}" method="POST" class="border-t border-amber-100 p-3 bg-white">
            @csrf
            <div class="flex gap-2 items-end">
                <textarea
                    id="reply-input"
                    name="message"
                    rows="1"
                    class="flex-1 border border-sand rounded-xl px-4 py-2.5 text-sm focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20 resize-none max-h-32"
                    placeholder="Ketik balasan..."
                    oninput="this.style.height='auto';this.style.height=this.scrollHeight+'px'"
                    required></textarea>
                <button type="submit" id="reply-btn" class="bg-sage-green text-white rounded-xl px-5 py-2.5 text-sm font-medium hover:brightness-110 transition-all flex items-center gap-1.5 disabled:opacity-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V5m0 0l-7 7m7-7l7 7"/></svg>
                    Kirim
                </button>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const sessionId   = @json($conversation->session_id);
    const convId      = @json($conversation->id);
    const replyUrl    = @json(route('admin.chat.reply', $conversation));
    const csrfToken   = document.querySelector('meta[name="csrf-token"]')?.content
                     || document.querySelector('input[name="_token"]')?.value
                     || '';
    const messagesEl  = document.getElementById('chat-messages');
    const replyForm   = document.getElementById('reply-form');
    const replyInput  = document.getElementById('reply-input');
    const replyBtn    = document.getElementById('reply-btn');
    const wsStatus    = document.getElementById('ws-status');

    // ── Scroll to bottom on load ───────────────────────────────────────────
    if (messagesEl) messagesEl.scrollTop = messagesEl.scrollHeight;

    // ── Append message bubble ──────────────────────────────────────────────
    function appendMessage(msg) {
        if (!messagesEl) return;
        if (document.getElementById('msg-' + msg.id)) return; // duplikasi

        const noMsg = document.getElementById('no-messages');
        if (noMsg) noMsg.remove();

        const isAdmin = msg.sender === 'admin';
        const d = new Date(msg.created_at);
        const timeStr = d.getHours().toString().padStart(2,'0') + ':' + d.getMinutes().toString().padStart(2,'0');

        let content = msg.message
            .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
            .replace(/\[gambar\](.*?)\[\/gambar\]/g,'<img src="/storage/$1" class="max-w-full rounded-lg my-1" alt="gambar">')
            .replace(/\n/g,'<br>');

        const wrap = document.createElement('div');
        wrap.id = 'msg-' + msg.id;
        wrap.className = 'flex ' + (isAdmin ? 'justify-end' : 'justify-start');
        wrap.innerHTML = `
            <div class="max-w-[80%] rounded-2xl px-4 py-2.5 text-sm leading-relaxed ${isAdmin ? 'bg-sage-green text-white rounded-tr-md' : 'bg-gray-100 text-dark-oak rounded-tl-md'}">
                ${content}
                <p class="text-[10px] mt-1.5 ${isAdmin ? 'text-white/70' : 'text-warm-gray'}" style="text-align:right">${timeStr}</p>
            </div>`;
        messagesEl.appendChild(wrap);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    // ── AJAX reply (bukan form submit biasa, agar halaman tidak reload) ────
    if (replyForm) {
        replyForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const msg = replyInput.value.trim();
            if (!msg) return;

            replyBtn.disabled = true;
            replyInput.value = '';
            replyInput.style.height = 'auto';

            try {
                const res = await fetch(replyUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ message: msg }),
                });
                const data = await res.json();
                if (data.success && data.message) {
                    // Append langsung — Echo juga akan menerima broadcast tapi guard duplikasi
                    appendMessage(data.message);
                } else {
                    replyInput.value = msg; // kembalikan kalau gagal
                    alert(data.message || 'Gagal mengirim pesan.');
                }
            } catch (err) {
                replyInput.value = msg;
                alert('Koneksi error, coba lagi.');
            } finally {
                replyBtn.disabled = false;
                replyInput.focus();
            }
        });
    }

    // ── Polling fallback — fetch messages every 8s ─────────────────────────
    let pollingInterval = null;
    const POLL_MS = 8000;
    const messagesUrl = '/admin/chat/' + convId + '/messages';

    async function pollMessages() {
        try {
            const res = await fetch(messagesUrl, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await res.json();
            if (data.messages) {
                data.messages.forEach(function (m) { appendMessage(m); });
            }
            if (data.status === 'closed') {
                location.reload();
            }
        } catch (e) {
            // silent — network errors saat polling tidak perlu di-notify
        }
    }

    function startPolling() {
        if (pollingInterval) return;
        // Tunggu 3 detik dulu baru mulai polling, kasih Echo kesempatan connect
        setTimeout(function () {
            pollMessages();
            pollingInterval = setInterval(pollMessages, POLL_MS);
        }, 3000);
    }

    // ── WebSocket via Echo ─────────────────────────────────────────────────
    function setStatus(text, color) {
        if (!wsStatus) return;
        wsStatus.textContent = text;
        wsStatus.className = `ml-auto text-[10px] px-2 py-0.5 rounded-full ${color}`;
    }

    if (window.Echo && sessionId) {
        // 1) Subscribe ke channel session customer
        window.Echo.private('chat.' + sessionId)
            .listen('MessageSent', function (e) {
                console.log('[Admin] MessageSent via chat.session:', e);
                if (e.message) appendMessage(e.message);
            })
            .listen('ChatClosed', function () {
                location.reload();
            });

        // 2) Subscribe ke channel global admin — tangkap semua pesan
        window.Echo.private('admin.chat')
            .listen('MessageSent', function (e) {
                console.log('[Admin] MessageSent via admin.chat:', e);
                // Hanya tampilkan jika untuk percakapan ini
                if (e.message && e.conversation_id === convId) {
                    appendMessage(e.message);
                }
            })
            .listen('ChatClosed', function (e) {
                if (e.conversation_id === convId) {
                    location.reload();
                }
            });

        // Cek status koneksi Pusher/Reverb
        const pusherConn = window.Echo.connector && window.Echo.connector.pusher;
        if (pusherConn) {
            pusherConn.connection.bind('connected', () => setStatus('● Terhubung', 'bg-green-100 text-green-600'));
            pusherConn.connection.bind('disconnected', () => setStatus('○ Terputus', 'bg-red-100 text-red-500'));
            pusherConn.connection.bind('failed', () => setStatus('✕ Gagal', 'bg-red-100 text-red-500'));
            if (pusherConn.connection.state === 'connected') {
                setStatus('● Terhubung', 'bg-green-100 text-green-600');
            }
        }

        console.log('[Admin Chat] Listening on: private-chat.' + sessionId + ' + private-admin.chat');
    } else {
        setStatus('✕ Polling 8dtk', 'bg-amber-100 text-amber-600');
        console.warn('[Admin Chat] Echo tidak tersedia, fallback ke polling.');
    }

    // ── Mulai polling sebagai safety net ────────────────────────────────────
    startPolling();
})();
</script>
@endpush
