<div x-data="{ openChat: false }" class="fixed bottom-6 right-6 z-50">
    <!-- Chat Button -->
    <button 
        @click="openChat = true" 
        class="bg-sage-green hover:brightness-110 text-white rounded-full p-4 shadow-lg flex items-center justify-center transition-all hover:-translate-y-1"
        x-show="!openChat"
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
    </button>

    <!-- Chat Box -->
    <div 
        x-show="openChat" 
        @click.away="openChat = false"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-8 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 scale-95"
        class="absolute bottom-0 right-0 w-[340px] bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100 flex flex-col"
        style="display: none; height: 500px; max-height: 80vh;"
    >
        <!-- Header -->
        <div class="bg-dark-oak p-4 flex justify-between items-center text-white shadow-md z-10">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 bg-sage-green rounded-full flex items-center justify-center font-playfair font-semibold text-lg border border-white/20">
                        CS
                    </div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-dark-oak rounded-full"></span>
                </div>
                <div>
                    <h3 class="font-medium text-[15px] leading-tight">Customer Service</h3>
                    <p class="text-[11px] text-white/70 mt-0.5">Membalas dalam beberapa menit</p>
                </div>
            </div>
            <button @click="openChat = false" class="text-white/60 hover:text-white transition-colors bg-white/10 hover:bg-white/20 rounded-full p-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Chat Body -->
        <div class="flex-1 bg-[#F9F9F9] p-4 overflow-y-auto flex flex-col gap-4 relative">
            <!-- Background pattern optional, leaving as plain or simple color for now -->
            
            <div class="text-center my-1">
                <span class="text-[10px] uppercase font-medium text-warm-gray bg-gray-200/50 px-3 py-1 rounded-full">Hari ini</span>
            </div>
            
            <!-- CS Bubble -->
            <div class="flex gap-2 w-full max-w-[88%]">
                <div class="w-7 h-7 bg-sage-green rounded-full flex-shrink-0 flex items-center justify-center text-white text-[10px] font-bold mt-1">CS</div>
                <div>
                    <div class="text-[10px] text-warm-gray mb-1 ml-1">CS Toko Buket • 09:00</div>
                    <div class="bg-white px-4 py-2.5 rounded-2xl rounded-tl-md shadow-sm border border-gray-100 text-sm text-dark-oak leading-relaxed">
                        Halo! 👋 Selamat datang. <br><br>Ada yang bisa dibantu untuk pemesanan buket atau informasi produk?
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-white border-t border-gray-100 flex items-end gap-2 drop-shadow-[0_-4px_6px_rgba(0,0,0,0.02)]">
            <button class="text-gray-400 hover:text-sage-green transition-colors p-2 rounded-full hover:bg-gray-50 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
            </button>
            <div class="relative w-full">
                <textarea 
                    rows="1"
                    class="w-full text-[13px] bg-gray-50 border border-gray-200 focus:border-sage-green focus:bg-white focus:ring-1 focus:ring-sage-green/20 rounded-2xl resize-none py-2.5 pl-4 pr-10 transition-all outline-none"
                    placeholder="Ketik pesan di sini..."
                    style="min-height: 42px; max-height: 100px;"
                ></textarea>
                <div class="absolute right-2 bottom-1.5 flex items-center gap-1">
                    <button class="text-gray-400 hover:text-gray-600 p-1 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </div>
            </div>
            <button class="bg-sage-green hover:bg-[#6b7b64] text-white rounded-full p-2.5 transition-colors shadow-sm flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 translate-x-[1px] translate-y-[-1px]" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                </svg>
            </button>
        </div>
    </div>
</div>
