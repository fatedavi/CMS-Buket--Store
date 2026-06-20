@extends('layouts.admin')

@section('content')
<div x-data="chatAdmin()" x-init="init()"
     class="h-[calc(100vh-6rem)] bg-white rounded-2xl border border-amber-100 overflow-hidden shadow-sm flex flex-col">

    {{-- VIEW: CONVERSATION LIST --}}
    <div x-show="!activeConvId" class="flex flex-col h-full">
        <div class="bg-dark-oak px-4 py-3 flex items-center justify-between flex-shrink-0">
            <h2 class="text-white font-medium text-sm">Percakapan</h2>
            <span class="text-[11px] text-white/60" x-text="filteredConversations.length + ' aktif'"></span>
        </div>
        <div class="relative px-3 py-2 bg-white border-b border-gray-100">
            <svg class="w-4 h-4 text-gray-400 absolute left-6 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" x-model="search" placeholder="Cari percakapan..." class="w-full bg-gray-100 rounded-lg pl-8 pr-3 py-2 text-xs outline-none focus:bg-gray-50 focus:ring-1 focus:ring-sage-green/30 transition-all">
        </div>
        <div class="flex-1 overflow-y-auto bg-white">
            <template x-for="conv in filteredConversations" :key="conv.id">
                <div @click="selectConversation(conv)"
                     class="flex items-center gap-3 px-4 py-3 cursor-pointer border-b border-gray-50 hover:bg-gray-50 transition-all"
                     :class="activeConvId === conv.id ? 'bg-sage-green/5' : ''">
                    <div class="relative flex-shrink-0">
                        <div class="w-11 h-11 rounded-full flex items-center justify-center text-sm font-semibold text-white"
                             :style="'background-color: ' + conv.color">
                            <span x-text="conv.initial"></span>
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 border-2 border-white rounded-full"
                              :class="conv.status === 'active' ? 'bg-green-500' : 'bg-gray-300'"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-medium text-gray-900 truncate" x-text="conv.customer_name || 'Pengunjung'"></h3>
                            <span class="text-[10px] text-gray-400 flex-shrink-0" x-text="timeAgo(conv.created_at)"></span>
                        </div>
                        <p class="text-[12px] text-gray-500 truncate mt-0.5" x-text="conv.messages_count + ' pesan'"></p>
                    </div>
                </div>
            </template>
            <div x-show="filteredConversations.length === 0" class="p-8 text-center">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <p class="text-xs text-gray-500">Tidak ada percakapan</p>
            </div>
        </div>
    </div>

    {{-- VIEW: CHAT --}}
    <div x-show="activeConvId" class="flex flex-col h-full">
        <div class="bg-dark-oak px-3 py-2.5 flex items-center gap-3 flex-shrink-0 shadow-sm z-10">
            <button @click="activeConvId = null" class="text-white/60 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m0 0l7-7m-7 7l7 7"/></svg>
            </button>
            <div class="relative">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-semibold text-white"
                     :style="'background-color: ' + (activeConv.color || '#6b8f54')">
                    <span x-text="activeConv.initial"></span>
                </div>
                <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 border-2 border-dark-oak rounded-full"
                      :class="convStatus === 'active' ? 'bg-green-400' : 'bg-gray-400'"></span>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-medium text-white truncate" x-text="activeConv.customer_name || 'Pengunjung'"></h3>
                <p class="text-[10px] mt-0.5" :class="convStatus === 'active' ? 'text-green-300' : 'text-white/50'"
                   x-text="convStatus === 'active' ? 'Online' : 'Offline'"></p>
            </div>
            <span id="ws-status" class="text-[10px] px-2 py-0.5 rounded-full bg-white/10 text-white/60 hidden md:inline">Menghubungkan...</span>
            <button @click="closeConversation()" x-show="convStatus === 'active'"
                    class="text-white/50 hover:text-terracotta hover:bg-white/10 rounded-full p-1.5 transition-all" title="Tutup percakapan">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-[#efeae2]" x-ref="messages" id="chat-messages">
            <template x-if="messages.length === 0 && !loadingMessages">
                <div class="flex items-center justify-center h-full text-center">
                    <p class="text-sm text-gray-500">Belum ada pesan. Ketik balasan di bawah untuk memulai.</p>
                </div>
            </template>
            <template x-if="loadingMessages">
                <div class="flex items-center justify-center h-full">
                    <div class="flex items-center gap-2 text-gray-500 text-sm">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Memuat pesan...
                    </div>
                </div>
            </template>
            <template x-for="msg in messages" :key="msg.id">
                <div class="flex" :class="msg.sender === 'admin' ? 'justify-end' : 'justify-start'" :id="'msg-' + msg.id">
                    <div class="max-w-[80%] rounded-2xl px-4 py-2.5 text-sm leading-relaxed shadow-sm border"
                         :class="msg.sender === 'admin' ? 'bg-sage-green text-white rounded-tr-md border-sage-green' : 'bg-white text-dark-oak rounded-tl-md border-gray-100'">
                        <template x-if="msg.sender === 'admin'">
                            <div>
                                <span x-text="msg.message" class="whitespace-pre-wrap"></span>
                            </div>
                        </template>
                        <template x-if="msg.sender !== 'admin'">
                            <div>
                                <div x-html="parseMessage(msg.message)" class="whitespace-pre-wrap"></div>
                            </div>
                        </template>
                        <p class="text-[10px] mt-1.5" :class="msg.sender === 'admin' ? 'text-white/70 text-right' : 'text-gray-400 text-right'">
                            <span x-text="formatTime(msg.created_at)"></span>
                        </p>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="convStatus === 'active'" class="bg-gray-50 px-3 py-2.5 border-t border-gray-200">
            <form @submit.prevent="sendReply" class="flex gap-2 items-end">
                <textarea x-model="replyText" rows="1"
                          class="flex-1 bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-sage-green focus:ring-1 focus:ring-sage-green/30 resize-none max-h-32"
                          placeholder="Ketik pesan..."
                          @keydown.enter.prevent="sendReply"
                          x-ref="replyInput"></textarea>
                <button type="submit" :disabled="!replyText.trim()"
                        class="bg-sage-green text-white rounded-full p-2.5 flex-shrink-0 hover:brightness-110 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V5m0 0l-7 7m7-7l7 7"/></svg>
                </button>
            </form>
        </div>
        <div x-show="convStatus !== 'active'" class="bg-gray-50 px-4 py-3 border-t border-gray-200 text-center">
            <span class="text-xs text-gray-400">Percakapan ini telah ditutup.</span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function chatAdmin() {
    return {
        conversations: [],
        search: '',
        activeConvId: null,
        activeConv: null,
        messages: [],
        replyText: '',
        convStatus: 'active',
        loadingMessages: false,
        echoChannels: [],
        pollTimer: null,

        get filteredConversations() {
            const s = this.search.toLowerCase();
            return this.conversations.filter(c =>
                (c.customer_name || '').toLowerCase().includes(s)
            );
        },

        init() {
            const convs = @json($convos);
            this.conversations = convs;
            this.listenEcho();
            this.startPolling();
        },

        selectConversation(conv) {
            this.activeConvId = conv.id;
            this.activeConv = conv;
            this.convStatus = conv.status;
            this.messages = [];
            this.replyText = '';
            this.loadMessages(conv.id);
            this.$nextTick(() => { if (this.$refs.replyInput) this.$refs.replyInput.focus(); });
        },

        async loadMessages(convId) {
            this.loadingMessages = true;
            try {
                const res = await fetch('/admin/chat/' + convId + '/messages', {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' }
                });
                const data = await res.json();
                this.messages = data.messages || [];
                this.convStatus = data.status || 'active';
                this.$nextTick(() => this.scrollDown());
            } catch (e) {
                console.error('Failed to load messages:', e);
            } finally {
                this.loadingMessages = false;
            }
        },

        async sendReply() {
            if (!this.replyText.trim() || !this.activeConvId) return;
            const msg = this.replyText.trim();
            this.replyText = '';

            try {
                const res = await fetch('/admin/chat/' + this.activeConvId + '/reply', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({ message: msg }),
                });
                const data = await res.json();
                if (data.success && data.message) {
                    this.messages.push(data.message);
                    this.$nextTick(() => this.scrollDown());
                } else {
                    this.replyText = msg;
                    alert(data.message || 'Gagal mengirim pesan.');
                }
            } catch (err) {
                this.replyText = msg;
                alert('Koneksi error, coba lagi.');
            }
        },

        async closeConversation() {
            if (!this.activeConvId) return;
            try {
                const res = await fetch('/admin/chat/' + this.activeConvId + '/close', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
                });
                if (res.ok) {
                    this.convStatus = 'closed';
                    const conv = this.conversations.find(c => c.id === this.activeConvId);
                    if (conv) conv.status = 'closed';
                }
            } catch (e) { console.error(e); }
        },

        parseMessage(text) {
            if (!text) return '';
            let result = text
                .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
                .replace(/\n/g,'<br>');
            result = result.replace(/\[gambar\](.*?)\[\/gambar\]/g, '<img src="/storage/$1" class="max-w-full rounded-lg my-1" alt="gambar">');
            return result;
        },

        formatTime(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr);
            return d.getHours().toString().padStart(2,'0') + ':' + d.getMinutes().toString().padStart(2,'0');
        },

        timeAgo(dateStr) {
            if (!dateStr) return '';
            const now = new Date();
            const d = new Date(dateStr);
            const diff = Math.floor((now - d) / 1000);
            if (diff < 60) return 'baru saja';
            if (diff < 3600) return Math.floor(diff / 60) + 'm';
            if (diff < 86400) return Math.floor(diff / 3600) + 'j';
            return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        },

        scrollDown() {
            const el = this.$refs.messages;
            if (el) {
                setTimeout(() => { el.scrollTop = el.scrollHeight; }, 50);
            }
        },

        listenEcho() {
            if (!window.Echo) return;
            window.Echo.private('admin.chat')
                .listen('MessageSent', (e) => {
                    if (e.conversation_id === this.activeConvId && e.message) {
                        if (!this.messages.find(m => m.id === e.message.id)) {
                            this.messages.push(e.message);
                            this.$nextTick(() => this.scrollDown());
                        }
                    }
                })
                .listen('ChatClosed', (e) => {
                    if (e.conversation_id === this.activeConvId) {
                        this.convStatus = 'closed';
                    }
                    const conv = this.conversations.find(c => c.id === e.conversation_id);
                    if (conv) conv.status = 'closed';
                });
        },

        startPolling() {
            setInterval(() => {
                if (this.activeConvId) {
                    this.loadMessages(this.activeConvId);
                }
            }, 8000);
        },
    };
}
</script>
@endpush
