@extends('layouts.app')

@section('content')
{{-- HERO SECTION --}}
<section class="relative min-h-[90vh] flex items-center">
    <div class="absolute inset-0 bg-gradient-to-br from-dark-oak/75 via-dark-oak/60 to-sage-green/50 bg-cover bg-center" style="background-image: url('{{ asset('images/hero-bg.png') }}')"></div>
    <div class="absolute inset-0 bg-dark-oak/50"></div>
    <div class="relative max-w-7xl mx-auto px-4 w-full">
        <div class="flex flex-col md:flex-row items-center justify-between gap-12">
            <div class="flex-1 max-w-xl" data-aos="fade-right">
                <span class="inline-block bg-white/15 backdrop-blur-sm border border-white/20 rounded-full px-4 py-1.5 text-xs text-blush mb-6">✦ Handcrafted · Sidoarjo</span>
                <h1 class="font-playfair text-4xl md:text-5xl lg:text-6xl text-white leading-tight font-semibold mb-6">Buket Bunga untuk Setiap <span class="text-blush">Momen Spesial</span></h1>
                <p class="text-white/85 text-lg leading-relaxed mb-8 max-w-md">Rangkaian bunga segar, dikerjakan dengan tangan. Tersedia untuk wisuda, anniversary, dan momen istimewa lainnya di Sidoarjo & sekitarnya.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('catalog.index') }}" class="bg-sage-green text-white rounded-full px-6 py-3 font-medium hover:brightness-110 transition-all">Lihat Katalog</a>
                    <a href="https://wa.me/6285649150049" class="bg-transparent border border-white text-white rounded-full px-6 py-3 font-medium hover:bg-white hover:text-dark-oak transition-all">Pesan via WA</a>
                </div>
            </div>
            <div class="hidden md:flex flex-col gap-3" data-aos="fade-left" data-aos-delay="200">
                <div class="bg-white/12 backdrop-blur-md border border-white/20 rounded-2xl p-4 text-white"><span class="font-playfair text-3xl block">200+</span><span class="text-sm text-white/70">Pesanan selesai</span></div>
                <div class="bg-white/12 backdrop-blur-md border border-white/20 rounded-2xl p-4 text-white"><span class="font-playfair text-3xl block">⭐ 4.9</span><span class="text-sm text-white/70">Rating pelanggan</span></div>
            </div>
        </div>
    </div>
</section>

{{-- FEATURE BAR --}}
<section class="bg-dark-oak py-4">
    <div class="max-w-7xl mx-auto px-4 flex flex-wrap justify-center gap-8" data-aos="fade-up" data-aos-delay="300">
        <div class="flex items-center gap-3"><span class="text-2xl">🌿</span><div><p class="text-sand font-medium">Bunga Segar Harian</p><p class="text-warm-gray text-sm">Kualitas terjamin</p></div></div>
        <div class="h-8 w-px bg-sage-green/30 hidden md:block"></div>
        <div class="flex items-center gap-3"><span class="text-2xl">🚚</span><div><p class="text-sand font-medium">Antar ke Rumah</p><p class="text-warm-gray text-sm">Area Sidoarjo</p></div></div>
        <div class="h-8 w-px bg-sage-green/30 hidden md:block"></div>
        <div class="flex items-center gap-3"><span class="text-2xl">💬</span><div><p class="text-sand font-medium">Order via WA</p><p class="text-warm-gray text-sm">Responsif</p></div></div>
        <div class="h-8 w-px bg-sage-green/30 hidden md:block"></div>
        <div class="flex items-center gap-3"><span class="text-2xl">🎨</span><div><p class="text-sand font-medium">Custom Design</p><p class="text-warm-gray text-sm">Sesuai request</p></div></div>
    </div>
</section>

{{-- FEATURED PRODUCTS --}}
<section class="py-16 bg-linen">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-8" data-aos="fade-up">
            <span class="text-xs uppercase tracking-widest text-terracotta">Pilihan Terbaik Kami</span>
            <h2 class="font-playfair text-3xl text-dark-oak mt-2">Produk Unggulan</h2>
        </div>
        <div x-data="{ current: 0, total: 3, interval: null, startAuto() { this.interval = setInterval(() => this.current = (this.current + 1) % 3, 4000) } }" x-init="startAuto()" @mouseenter="clearInterval(interval)" class="relative overflow-hidden" data-aos="fade-up" data-aos-delay="150">
            <div class="flex transition-transform duration-500" :style="`transform: translateX(-${current * 100}%)`">
                @for($i = 0; $i < 3; $i++)
                <div class="w-full flex-shrink-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach(array_slice($featuredProducts, $i * 3, 3) as $product)
                        <a href="{{ route('catalog.show', $product['slug']) }}" class="bg-white border border-amber-100 rounded-xl overflow-hidden group">
                            <div class="relative aspect-[4/3]">
                                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover">
                                @if($product['badge'])<span class="absolute top-2 left-2 bg-sage-green text-white text-xs rounded-lg px-2 py-0.5">{{ $product['badge'] }}</span>@endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-medium text-dark-oak text-sm mb-1">{{ $product['name'] }}</h3>
                                <p class="text-xs text-warm-gray mb-3">{{ $product['category'] }}</p>
                                <div class="bg-sage-green text-white rounded-lg py-2 text-xs text-center">Pesan via WA</div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>
</section>

{{-- KATEGORI GRID --}}
<section class="py-16 bg-cream">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-8" data-aos="fade-in">
            <span class="text-xs uppercase tracking-widest text-terracotta">Jelajahi Koleksi</span>
            <h2 class="font-playfair text-3xl text-dark-oak mt-2">Kategori Buket</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($categories as $category)
            <a href="{{ route('catalog.index') }}?kategori={{ $category['slug'] }}" class="group relative h-36 rounded-xl overflow-hidden" data-aos="zoom-in" data-aos-delay="{{ $loop->iteration * 100 }}">
                <img src="{{ $category['image'] }}" alt="{{ $category['name'] }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-dark-oak/80 to-transparent"></div>
                <div class="absolute bottom-3 left-3">
                    <h3 class="text-white font-medium text-sm">{{ $category['name'] }}</h3>
                    <p class="text-white/70 text-xs">{{ $category['count'] }} produk</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- TESTIMONIAL --}}
<section class="py-16 bg-linen">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-8" data-aos="fade-up">
            <span class="text-xs uppercase tracking-widest text-terracotta">Kata Mereka</span>
            <h2 class="font-playfair text-3xl text-dark-oak mt-2">Pelanggan Puas Kami</h2>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-12 bg-sage-green">
    <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-4" data-aos="flip-up">
        <div>
            <p class="text-white font-medium">Tidak menemukan yang cocok?</p>
            <p class="text-white/80 text-sm">Kami terima pesanan custom.</p>
        </div>
        <a href="https://wa.me/6285649150049" class="bg-white text-sage-green rounded-full px-6 py-3 font-semibold">Hubungi via WhatsApp →</a>
    </div>
</section>
@endsection