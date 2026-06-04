@extends('layouts.app')

@section('content')
{{-- HERO SLIDESHOW --}}
<section class="relative min-h-[90vh] flex items-center overflow-hidden"
    x-data="{
        slides: {{ Js::from($heroSlides) }},
        current: 0,
        timer: null,
        init() {
            this.timer = setInterval(() => {
                this.current = (this.current + 1) % this.slides.length;
            }, 5000);
        },
        goTo(i) { this.current = i; },
    }"
    x-init="init()"
    @mouseenter="clearInterval(timer)"
    @mouseleave="timer = setInterval(() => current = (current + 1) % slides.length, 5000)">

    {{-- Background slides --}}
    <template x-for="(slide, i) in slides" :key="i">
        <div class="absolute inset-0 bg-cover bg-center transition-all duration-1000"
            :class="i === current ? 'opacity-100' : 'opacity-0'"
            :style="`background-image: url('${slide}')`">
        </div>
    </template>

    <div class="absolute inset-0 bg-gradient-to-r from-dark-oak/70 via-dark-oak/40 to-dark-oak/30"></div>

    {{-- Decorative floating circles --}}
    <div class="absolute top-20 right-10 w-64 h-64 rounded-full bg-blush/10 blur-3xl"></div>
    <div class="absolute bottom-20 left-10 w-80 h-80 rounded-full bg-sage-green/10 blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-4 w-full">
        <div class="flex flex-col items-start max-w-2xl">
            <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full px-4 py-1.5 text-xs text-blush mb-6" data-aos="fade-down">
                <span class="w-1.5 h-1.5 rounded-full bg-blush animate-pulse"></span>
                Handcrafted dengan Cinta · Sidoarjo
            </span>
            <h1 class="font-playfair text-4xl md:text-5xl lg:text-7xl text-white leading-tight font-semibold mb-6" data-aos="fade-up" data-aos-delay="100">
                Buket Bunga untuk Setiap <span class="text-blush italic">Momen Spesial</span>
            </h1>
            <p class="text-white/80 text-lg leading-relaxed mb-8 max-w-lg" data-aos="fade-up" data-aos-delay="200">
                Rangkaian bunga segar, dikerjakan dengan tangan. Tersedia untuk wisuda, anniversary, dan momen istimewa lainnya di Sidoarjo & sekitarnya.
            </p>
            <div class="flex flex-wrap gap-4" data-aos="fade-up" data-aos-delay="300">
                <a href="{{ route('catalog.index') }}" class="group bg-sage-green text-white rounded-full px-8 py-3.5 font-medium hover:brightness-110 transition-all inline-flex items-center gap-2">
                    Lihat Katalog
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="https://wa.me/6285649150049" class="group bg-white/10 backdrop-blur-md border border-white/30 text-white rounded-full px-8 py-3.5 font-medium hover:bg-white hover:text-dark-oak transition-all inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Pesan via WA
                </a>
            </div>

            {{-- Slide dots --}}
            <div class="flex gap-2 mt-8" data-aos="fade-up" data-aos-delay="400">
                <template x-for="(slide, i) in slides" :key="i">
                    <button @click="goTo(i); current = i"
                        class="h-2 rounded-full transition-all duration-300"
                        :class="current === i ? 'w-8 bg-white' : 'w-2 bg-white/40 hover:bg-white/60'">
                    </button>
                </template>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 2: MENGAPA MEMILIH KAMI --}}
<section class="relative py-20 bg-linen overflow-hidden">
    {{-- Subtle background ornament --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-sage-green/5 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-blush/5 rounded-full translate-y-1/2 -translate-x-1/2 blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-4">
        <div class="text-center mb-14" data-aos="fade-up">
            <span class="text-xs uppercase tracking-[0.2em] text-terracotta font-medium">Mengapa Memilih Kami</span>
            <h2 class="font-playfair text-3xl md:text-4xl text-dark-oak mt-3">Kenapa <span class="italic text-sage-green">Kami</span> Berbeda?</h2>
            <p class="text-warm-gray text-sm mt-3 max-w-md mx-auto">Kami merangkai setiap buket dengan penuh perhatian, dari pemilihan bunga hingga pengiriman.</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Card 1 --}}
            <div class="group bg-white rounded-2xl p-6 border border-amber-100/60 hover:border-sage-green/30 hover:shadow-xl hover:shadow-sage-green/5 transition-all duration-500" data-aos="fade-up" data-aos-delay="0">
                <div class="w-14 h-14 rounded-xl bg-sage-green/10 flex items-center justify-center mb-5 group-hover:bg-sage-green group-hover:scale-110 transition-all duration-500">
                    <svg class="w-7 h-7 text-sage-green group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
                <h3 class="font-playfair text-lg text-dark-oak mb-2">Bunga Segar Harian</h3>
                <p class="text-warm-gray text-sm leading-relaxed">Kami memilih langsung dari petani lokal setiap pagi. Bunga segar, tahan lebih lama.</p>
            </div>

            {{-- Card 2 --}}
            <div class="group bg-white rounded-2xl p-6 border border-amber-100/60 hover:border-sage-green/30 hover:shadow-xl hover:shadow-sage-green/5 transition-all duration-500" data-aos="fade-up" data-aos-delay="100">
                <div class="w-14 h-14 rounded-xl bg-blush/10 flex items-center justify-center mb-5 group-hover:bg-terracotta group-hover:scale-110 transition-all duration-500">
                    <svg class="w-7 h-7 text-terracotta group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/></svg>
                </div>
                <h3 class="font-playfair text-lg text-dark-oak mb-2">Custom Design</h3>
                <p class="text-warm-gray text-sm leading-relaxed">Sesuaikan warna, jenis bunga, dan dekorasi sesuai keinginanmu. Bebas request!</p>
            </div>

            {{-- Card 3 --}}
            <div class="group bg-white rounded-2xl p-6 border border-amber-100/60 hover:border-sage-green/30 hover:shadow-xl hover:shadow-sage-green/5 transition-all duration-500" data-aos="fade-up" data-aos-delay="200">
                <div class="w-14 h-14 rounded-xl bg-sage-green/10 flex items-center justify-center mb-5 group-hover:bg-sage-green group-hover:scale-110 transition-all duration-500">
                    <svg class="w-7 h-7 text-sage-green group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                </div>
                <h3 class="font-playfair text-lg text-dark-oak mb-2">Pengiriman Cepat</h3>
                <p class="text-warm-gray text-sm leading-relaxed">Area Sidoarjo bebas ongkir. Rangkai di hari yang sama, kirim langsung.</p>
            </div>

            {{-- Card 4 --}}
            <div class="group bg-white rounded-2xl p-6 border border-amber-100/60 hover:border-sage-green/30 hover:shadow-xl hover:shadow-sage-green/5 transition-all duration-500" data-aos="fade-up" data-aos-delay="300">
                <div class="w-14 h-14 rounded-xl bg-blush/10 flex items-center justify-center mb-5 group-hover:bg-terracotta group-hover:scale-110 transition-all duration-500">
                    <svg class="w-7 h-7 text-terracotta group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                </div>
                <h3 class="font-playfair text-lg text-dark-oak mb-2">Konsultasi Gratis</h3>
                <p class="text-warm-gray text-sm leading-relaxed">Bingung pilih buket? Tim kami siap bantu via WhatsApp, gratis!</p>
            </div>
        </div>
    </div>
</section>

{{-- FEATURE BAR --}}
<section class="bg-dark-oak py-4">
    <div class="max-w-7xl mx-auto px-4 flex flex-wrap justify-center gap-8" data-aos="fade-up">
        <div class="flex items-center gap-3"><x-icons.leaf class="w-5 h-5 text-sage-green" /><div><p class="text-sand font-medium text-sm">Bunga Segar Harian</p></div></div>
        <div class="h-8 w-px bg-sage-green/30 hidden md:block"></div>
        <div class="flex items-center gap-3"><x-icons.truck class="w-5 h-5 text-sage-green" /><div><p class="text-sand font-medium text-sm">Antar ke Rumah</p></div></div>
        <div class="h-8 w-px bg-sage-green/30 hidden md:block"></div>
        <div class="flex items-center gap-3"><x-icons.chat class="w-5 h-5 text-sage-green" /><div><p class="text-sand font-medium text-sm">Order via WA</p></div></div>
        <div class="h-8 w-px bg-sage-green/30 hidden md:block"></div>
        <div class="flex items-center gap-3"><x-icons.palette class="w-5 h-5 text-sage-green" /><div><p class="text-sand font-medium text-sm">Custom Design</p></div></div>
    </div>
</section>

{{-- FEATURED PRODUCTS --}}
<section class="py-16 md:py-20 bg-linen">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-10" data-aos="fade-up">
            <span class="text-xs uppercase tracking-[0.2em] text-terracotta font-medium">Pilihan Terbaik Kami</span>
            <h2 class="font-playfair text-3xl md:text-4xl text-dark-oak mt-3">Produk <span class="italic text-sage-green">Unggulan</span></h2>
        </div>
        <div x-data="{ current: 0, total: 3, interval: null, startAuto() { this.interval = setInterval(() => this.current = (this.current + 1) % 3, 4000) } }" x-init="startAuto()" @mouseenter="clearInterval(interval)" class="relative overflow-hidden" data-aos="fade-up" data-aos-delay="150">
            <div class="flex transition-transform duration-500" :style="`transform: translateX(-${current * 100}%)`">
                @for($i = 0; $i < 3; $i++)
                <div class="w-full flex-shrink-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($featuredProducts->slice($i * 3, 3) as $product)
                        <a href="{{ route('catalog.show', $product['slug']) }}" class="group bg-white border border-amber-100 rounded-xl overflow-hidden hover:shadow-xl hover:shadow-amber-100/50 hover:-translate-y-1 transition-all duration-500">
                            <div class="relative aspect-[4/3] overflow-hidden">
                                <img src="{{ $product->image_url }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                @if($product['badge'])<span class="absolute top-3 left-3 bg-sage-green text-white text-xs rounded-lg px-2.5 py-1 font-medium">{{ $product['badge'] }}</span>@endif
                                <div class="absolute inset-0 bg-gradient-to-t from-dark-oak/0 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            </div>
                            <div class="p-5">
                                <h3 class="font-playfair text-base text-dark-oak mb-1">{{ $product['name'] }}</h3>
                                <p class="text-xs text-warm-gray mb-4">{{ $product['category'] }}</p>
                                <div class="bg-sage-green text-white rounded-lg py-2.5 text-xs text-center font-medium group-hover:brightness-110 transition-all">Pesan via WA</div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endfor
            </div>
            {{-- Dots --}}
            <div class="flex justify-center gap-2 mt-8">
                <template x-for="i in total" :key="i">
                    <button @click="current = i - 1" class="w-2 h-2 rounded-full transition-all duration-300" :class="current === i - 1 ? 'bg-sage-green w-6' : 'bg-amber-200'"></button>
                </template>
            </div>
        </div>
    </div>
</section>

{{-- KATEGORI GRID --}}
<section class="py-16 md:py-20 bg-cream">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-10" data-aos="fade-up">
            <span class="text-xs uppercase tracking-[0.2em] text-terracotta font-medium">Jelajahi Koleksi</span>
            <h2 class="font-playfair text-3xl md:text-4xl text-dark-oak mt-3">Kategori <span class="italic text-sage-green">Buket</span></h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($categories as $category)
            <a href="{{ route('catalog.index') }}?kategori={{ $category['slug'] }}" class="group relative h-40 rounded-xl overflow-hidden" data-aos="zoom-in" data-aos-delay="{{ $loop->iteration * 80 }}">
                <img src="{{ $category['image'] }}" alt="{{ $category['name'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-dark-oak/80 via-dark-oak/20 to-transparent"></div>
                <div class="absolute bottom-4 left-4">
                    <h3 class="text-white font-playfair text-base">{{ $category['name'] }}</h3>
                    <p class="text-white/70 text-xs mt-0.5">{{ $category['count'] }} produk</p>
                </div>
                <div class="absolute inset-0 border-2 border-transparent group-hover:border-sage-green/50 rounded-xl transition-all duration-500"></div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- TIPS & FUNFACT --}}
<section class="relative py-16 md:py-20 bg-linen overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-sage-green/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-blush/5 rounded-full blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-xs uppercase tracking-[0.2em] text-terracotta font-medium">Tips & Funfact</span>
            <h2 class="font-playfair text-3xl md:text-4xl text-dark-oak mt-3">Tips Memilih <span class="italic text-sage-green">Produk</span></h2>
            <p class="text-warm-gray text-sm mt-3 max-w-md mx-auto">Panduan dan fakta menarik seputar buket bunga untuk membantu Anda memilih</p>
        </div>

        @if($tips->count())
        <div class="flex flex-col gap-8 md:gap-12 max-w-4xl mx-auto">
            @foreach($tips as $tip)
            <div class="bg-white p-6 md:p-8 rounded-2xl border-2 border-black/20 hover:border-black/50 transition-all w-full md:w-4/5 {{ $loop->even ? 'self-end md:self-end' : 'self-start' }}"
                 data-aos="{{ $loop->even ? 'fade-left' : 'fade-right' }}" data-aos-delay="{{ $loop->iteration * 80 }}">
                <div class="flex items-center gap-4 mb-3">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center{{ $loop->even ? ' bg-terracotta/10' : ' bg-sage-green/10' }}">
                        <x-icons.tip :icon="$tip->icon ?? 'lightbulb'" class="w-5 h-5 text-sage-green" />
                    </div>
                    <h3 class="font-playfair text-lg text-dark-oak">{{ $tip->title }}</h3>
                </div>
                <p class="text-warm-gray text-sm leading-relaxed">{{ $tip->content }}</p>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center text-warm-gray text-sm py-12">
            Belum ada tips. Kunjungi halaman <a href="{{ route('blog.index') }}" class="text-sage-green underline">blog</a> kami untuk inspirasi.
        </div>
        @endif
    </div>
</section>

{{-- STATS SECTION --}}
<section class="relative py-16 md:py-20 bg-dark-oak overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-sage-green/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-blush/5 rounded-full blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-xs uppercase tracking-[0.2em] text-sand/60 font-medium">Pencapaian Kami</span>
            <h2 class="font-playfair text-3xl md:text-4xl text-sand mt-3">Angka yang <span class="italic text-sage-green">Berbicara</span></h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6" data-aos="fade-up" data-aos-delay="100">
            {{-- Stat 1 --}}
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 md:p-8 text-center hover:bg-white/10 transition-all duration-500 group">
                <span class="font-playfair text-4xl md:text-5xl text-sage-green block mb-2">200+</span>
                <span class="text-sand/70 text-sm">Pesanan Selesai</span>
                <div class="w-8 h-0.5 bg-sage-green/50 mx-auto mt-3 group-hover:w-12 transition-all duration-500"></div>
            </div>

            {{-- Stat 2 --}}
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 md:p-8 text-center hover:bg-white/10 transition-all duration-500 group">
                <span class="font-playfair text-4xl md:text-5xl text-blush block mb-2">⭐ 4.9</span>
                <span class="text-sand/70 text-sm">Rating Pelanggan</span>
                <div class="w-8 h-0.5 bg-blush/50 mx-auto mt-3 group-hover:w-12 transition-all duration-500"></div>
            </div>

            {{-- Stat 3 --}}
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 md:p-8 text-center hover:bg-white/10 transition-all duration-500 group">
                <span class="font-playfair text-4xl md:text-5xl text-sage-green block mb-2">50+</span>
                <span class="text-sand/70 text-sm">Varian Produk</span>
                <div class="w-8 h-0.5 bg-sage-green/50 mx-auto mt-3 group-hover:w-12 transition-all duration-500"></div>
            </div>

            {{-- Stat 4 --}}
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 md:p-8 text-center hover:bg-white/10 transition-all duration-500 group">
                <span class="font-playfair text-4xl md:text-5xl text-blush block mb-2">100%</span>
                <span class="text-sand/70 text-sm">Kepuasan Pelanggan</span>
                <div class="w-8 h-0.5 bg-blush/50 mx-auto mt-3 group-hover:w-12 transition-all duration-500"></div>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="relative py-16 bg-gradient-to-r from-sage-green to-[#6b8f54] overflow-hidden">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=%2760%27 height=%2760%27 viewBox=%270 0 60 60%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cg fill=%27none%27 fill-rule=%27evenodd%27%3E%3Cg fill=%27%23ffffff%27 fill-opacity=%270.04%27%3E%3Cpath d=%27M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%27/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-60"></div>
    <div class="relative max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6" data-aos="fade-up">
            <div class="text-center md:text-left">
                <p class="text-white text-xl md:text-2xl font-playfair">Tidak menemukan yang cocok?</p>
                <p class="text-white/75 text-sm mt-1">Kami terima pesanan custom. Diskusikan dengan tim kami!</p>
            </div>
            <a href="https://wa.me/6285649150049" class="group inline-flex items-center gap-2 bg-white text-sage-green rounded-full px-8 py-3.5 font-semibold hover:shadow-xl hover:shadow-black/10 hover:-translate-y-0.5 transition-all">
                Hubungi via WhatsApp
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endsection