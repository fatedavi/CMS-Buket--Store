<div x-data="chatWidget()" class="fixed bottom-6 right-6 z-50">
    <!-- Chat Button -->
    <button
        @click="open = true; initChat()"
        class="bg-sage-green hover:brightness-110 text-white rounded-full p-4 shadow-lg flex items-center justify-center transition-all hover:-translate-y-1 relative"
        x-show="!open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-50"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-50"
        style="display: none;"
        x-init="$el.style.display = 'flex'"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <span class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full border-2 border-white transition-colors duration-300" :class="adminOnline ? 'bg-green-500' : 'bg-gray-400'"></span>
    </button>

    <!-- Chat Box -->
    <div
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-8 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 scale-95"
        class="absolute bottom-0 right-0 w-[340px] bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100 flex flex-col"
        style="display: none; height: 500px; max-height: 80vh;"
    >
        <div class="bg-dark-oak p-4 flex justify-between items-center text-white shadow-md z-10">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 bg-sage-green rounded-full flex items-center justify-center font-playfair font-semibold text-lg border border-white/20">CS</div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 border-2 border-dark-oak rounded-full transition-colors duration-300" :class="adminOnline ? 'bg-green-500' : 'bg-gray-400'"></span>
                </div>
                <div>
                    <h3 class="font-medium text-[15px] leading-tight">Customer Service</h3>
                    <p class="text-[11px] mt-0.5 transition-colors duration-300" :class="adminOnline ? 'text-green-300' : 'text-white/50'" x-text="adminOnline ? 'Online' : 'Offline'"></p>
                </div>
            </div>
            <button @click="open = false; stopEcho()" class="text-white/60 hover:text-white transition-colors bg-white/10 hover:bg-white/20 rounded-full p-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="flex-1 bg-[#F9F9F9] p-4 overflow-y-auto flex flex-col gap-4" x-ref="messages">
            <template x-if="closed">
                <div class="flex flex-col items-center justify-center h-full text-center">
                    <div class="w-14 h-14 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <p class="text-sm text-dark-oak font-medium mb-1">Chat Ditutup</p>
                    <p class="text-xs text-warm-gray mb-4">Percakapan ini telah ditutup oleh admin.</p>
                    <button @click="startNewSession" class="bg-sage-green text-white text-sm rounded-xl px-5 py-2.5 font-medium hover:brightness-110 transition-all">
                        Mulai Chat Baru
                    </button>
                </div>
            </template>
            <template x-if="!closed">
                <template x-for="msg in messages" :key="msg.id">
                    <div>
                        <div x-show="msg.sender === 'system'" class="text-center py-2">
                            <span class="inline-block bg-gray-200 text-gray-500 text-[11px] px-3 py-1.5 rounded-full" x-text="msg.message"></span>
                        </div>
                        <div x-show="msg.sender !== 'system'" class="flex" :class="msg.sender === 'customer' ? 'justify-end' : 'max-w-[88%]'">
                            <div x-show="msg.sender === 'admin'" class="w-7 h-7 bg-sage-green rounded-full flex-shrink-0 flex items-center justify-center text-white text-[10px] font-bold mt-1 mr-2">CS</div>
                            <div>
                                <div class="text-[10px] text-warm-gray mb-1" :class="msg.sender === 'customer' ? 'text-right mr-1' : 'ml-1'" x-text="formatTime(msg.created_at, msg.sender)"></div>
                                <div class="px-4 py-2.5 rounded-2xl shadow-sm border text-sm leading-relaxed" :class="msg.sender === 'customer' ? 'bg-sage-green text-white rounded-tr-md border-sage-green' : 'bg-white text-dark-oak rounded-tl-md border-gray-100'">
                                    <template x-for="part in parseMessage(msg.message)" :key="part.type">
                                        <div>
                                            <span x-show="part.type === 'text'" x-text="part.content"></span>
                                            <img x-show="part.type === 'image'" :src="part.content" class="max-w-full rounded-lg mt-1 cursor-pointer" @click="previewImage = part.content">
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
        </div>

        <div class="p-3 bg-white border-t border-gray-100" x-show="!closed">
            <div x-show="errorMsg" x-text="errorMsg"
                 class="bg-terracotta/10 text-terracotta text-[11px] px-3 py-2 rounded-xl mb-2 text-center"
                 style="display: none;"></div>
            <form @submit.prevent="sendMessage" class="flex items-end gap-2">
                <div class="relative w-full">
                    <textarea
                        x-model="newMessage"
                        rows="1"
                        class="w-full text-[13px] bg-gray-50 border border-gray-200 focus:border-sage-green focus:bg-white focus:ring-1 focus:ring-sage-green/20 rounded-2xl resize-none py-2.5 pl-4 pr-10 transition-all outline-none"
                        placeholder="Ketik pesan..."
                        style="min-height: 42px; max-height: 100px;"
                        @keydown.enter.prevent="sendMessage"
                    ></textarea>
                    <div class="absolute right-2 bottom-1.5 flex items-center gap-1">
                        <template x-if="isLoggedIn">
                            <button type="button" @click="uploadImage" class="text-gray-400 hover:text-gray-600 p-1 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            </button>
                        </template>
                    </div>
                </div>
                <button type="submit" :disabled="!newMessage.trim() && !selectedImage" class="bg-sage-green hover:brightness-110 text-white rounded-full p-2.5 transition-all shadow-sm flex-shrink-0 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
                </button>
            </form>
            <input type="file" accept="image/*" x-ref="fileInput" class="hidden" @change="handleFileSelect">
            <template x-if="!isLoggedIn">
                <p class="text-[10px] text-warm-gray mt-1 text-center"><a href="{{ route('login') }}" class="text-sage-green underline">Masuk</a> untuk kirim gambar</p>
            </template>
            <template x-if="selectedImage">
                <div class="mt-2 flex items-center gap-2 bg-gray-50 rounded-lg p-2">
                    <img :src="selectedImageUrl" class="w-10 h-10 rounded object-cover">
                    <span class="text-xs text-warm-gray flex-1 truncate">1 gambar terpilih</span>
                    <button type="button" @click="selectedImage = null" class="text-terracotta text-xs">Hapus</button>
                </div>
            </template>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div x-show="previewImage" @click.away="previewImage = null" class="fixed inset-0 z-[100] bg-black/70 flex items-center justify-center p-4" style="display: none;">
        <img :src="previewImage" class="max-w-full max-h-full rounded-2xl">
        <button @click="previewImage = null" class="absolute top-4 right-4 text-white bg-black/30 rounded-full p-2">&times;</button>
    </div>
</div>

<script>
function chatWidget() {
    return {
        open: false,
        messages: [],
        newMessage: '',
        loading: false,
        isLoggedIn: {{ auth()->check() ? 'true' : 'false' }},
        sessionId: {!! auth()->check() ? "'user_" . auth()->id() . "'" : "null" !!} || localStorage.getItem('chat_session_id') || '',
        selectedImage: null,
        selectedImageUrl: null,
        previewImage: null,
        adminOnline: false,
        closed: false,
        errorMsg: '',
        echoChannel: null,
        pollTimer: null,
        adminPollTimer: null,

        init() {
            this.startAdminPolling();
        },

        initChat() {
            if (!this.sessionId) {
                this.sessionId = 'chat_' + Date.now() + '_' + Math.random().toString(36).slice(2, 9);
                localStorage.setItem('chat_session_id', this.sessionId);
            }
            this.loadMessages();
            this.listenEcho();
            this.startPolling();
            this.startAdminPolling();
        },

        listenEcho() {
            if (this.echoChannel) return;
            if (!window.Echo) return;
            this.echoChannel = window.Echo.private('chat.' + this.sessionId)
                .listen('MessageSent', (e) => {
                    if (!this.messages.find(m => m.id === e.message.id)) {
                        this.messages.push(e.message);
                        this.$nextTick(() => this.scrollDown());
                    }
                })
                .listen('ChatClosed', () => {
                    this.closed = true;
                });
        },

        stopEcho() {
            if (this.echoChannel) {
                window.Echo.leave('chat.' + this.sessionId);
                this.echoChannel = null;
            }
            this.stopPolling();
            this.stopAdminPolling();
        },

        startPolling() {
            this.stopPolling();
            this.pollTimer = setInterval(() => {
                if (this.open && !this.closed) {
                    this.loadMessages();
                }
            }, 8000);
        },

        stopPolling() {
            if (this.pollTimer) {
                clearInterval(this.pollTimer);
                this.pollTimer = null;
            }
        },

        startAdminPolling() {
            this.stopAdminPolling();
            this.checkAdminStatus();
            this.adminPollTimer = setInterval(() => {
                this.checkAdminStatus();
            }, 30000);
        },

        stopAdminPolling() {
            if (this.adminPollTimer) {
                clearInterval(this.adminPollTimer);
                this.adminPollTimer = null;
            }
        },

        async checkAdminStatus() {
            try {
                const res = await fetch('/chat/admin-status');
                const data = await res.json();
                this.adminOnline = data.online;
            } catch (e) {
                // silent — fallback ke status terakhir
            }
        },

        async loadMessages() {
            try {
                const res = await fetch(`/chat/messages?session_id=${this.sessionId}`);
                const data = await res.json();
                this.messages = data.messages || [];
                this.closed = data.closed || false;
                this.$nextTick(() => this.scrollDown());
            } catch (e) { console.error(e); }
        },

        async sendMessage() {
            if (!this.newMessage.trim() && !this.selectedImage) return;
            const formData = new FormData();
            formData.append('message', this.newMessage.trim());
            formData.append('session_id', this.sessionId);
            if (this.selectedImage) formData.append('image', this.selectedImage);

            const messageText = this.newMessage.trim();
            this.newMessage = '';
            this.loading = true;

            try {
                const res = await fetch('/chat/send', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: formData,
                });
                const data = await res.json();
                if (data.success) {
                    this.messages.push(data.message);
                    this.selectedImage = null;
                    this.selectedImageUrl = null;
                    this.$nextTick(() => this.scrollDown());
                } else {
                    this.errorMsg = data.message || 'Pesan gagal dikirim.';
                    setTimeout(() => { this.errorMsg = ''; }, 4000);
                }
            } catch (e) {
                this.errorMsg = 'Gagal terhubung ke server.';
                setTimeout(() => { this.errorMsg = ''; }, 4000);
                this.newMessage = messageText;
            }
            finally { this.loading = false; }
        },

        startNewSession() {
            this.stopEcho();
            this.closed = false;
            this.messages = [];
            if (!this.isLoggedIn) {
                this.sessionId = 'chat_' + Date.now() + '_' + Math.random().toString(36).slice(2, 9);
                localStorage.setItem('chat_session_id', this.sessionId);
            }
            this.$nextTick(() => this.scrollDown());
            this.listenEcho();
        },

        uploadImage() {
            this.$refs.fileInput.click();
        },

        handleFileSelect(e) {
            const file = e.target.files[0];
            if (file) {
                this.selectedImage = file;
                this.selectedImageUrl = URL.createObjectURL(file);
            }
            e.target.value = '';
        },

        parseMessage(msg) {
            if (!msg) return [{ type: 'text', content: '' }];
            const parts = [];
            const regex = /\[gambar\](.*?)\[\/gambar\]/g;
            let last = 0, match;
            while ((match = regex.exec(msg)) !== null) {
                if (match.index > last) parts.push({ type: 'text', content: msg.slice(last, match.index) });
                parts.push({ type: 'image', content: '/storage/' + match[1] });
                last = match.index + match[0].length;
            }
            if (last < msg.length) parts.push({ type: 'text', content: msg.slice(last) });
            return parts;
        },

        scrollDown() {
            const el = this.$refs.messages;
            if (el) el.scrollTop = el.scrollHeight;
        },

        formatTime(dateStr, sender) {
            if (!dateStr) return '';
            const d = new Date(dateStr);
            const time = d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
            const label = sender === 'customer' ? 'Anda' : 'CS Toko Buket';
            return label + ' • ' + time;
        }
    };
}
</script>
