@extends('layouts.app')

@section('content')
<section class="relative h-[280px] flex items-center">
    <div class="absolute inset-0 bg-dark-oak/60 bg-[url('https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=1920&q=80')] bg-cover bg-center"></div>
    <div class="absolute inset-0 bg-dark-oak/60"></div>
    <div class="relative max-w-7xl mx-auto px-4 w-full text-center">
        <p class="text-white/60 text-sm mb-2">Beranda / Katalog</p>
        <h1 class="font-playfair text-3xl text-white">Katalog Buket Bunga</h1>
        <p class="text-white/80 text-sm mt-2">Temukan buket sempurna untuk setiap momen</p>
    </div>
</section>

@php
    $minPrice = $minPrice ?? 0;
    $maxPrice = $maxPrice ?? 500000;
@endphp

<div x-data="{
    activeCategory: 'Semua',
    minPrice: {{ $minPrice ?? 0 }},
    maxPrice: {{ $maxPrice ?? 500000 }},
    priceMin: {{ $minPrice ?? 0 }},
    priceMax: {{ $maxPrice ?? 500000 }},
    get filteredProducts() {
        return {{ $products->map(fn($p) => [
            'name' => $p->name,
            'category' => $p->category->name ?? '',
            'price' => $p->price ? (float)$p->price : 0,
            'slug' => $p->slug,
        ])->toJson() }}.filter(p =>
            (this.activeCategory === 'Semua' || p.category === this.activeCategory)
            && p.price >= this.priceMin
            && (this.priceMax === 0 || p.price <= this.priceMax)
        );
    }
}">
<section class="py-8 bg-linen border-b border-amber-100">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-wrap gap-2 mb-4">
            @foreach($categories as $cat)
            <button @click="activeCategory = '{{ $cat }}'"
                class="rounded-full px-4 py-1.5 text-sm border transition-all"
                :class="activeCategory === '{{ $cat }}' ? 'bg-sage-green text-white border-sage-green' : 'border-sand text-warm-gray hover:border-sage-green'">
                {{ $cat }}
            </button>
            @endforeach
        </div>

        <div class="flex flex-wrap items-center gap-4 pt-3 border-t border-amber-100">
            <span class="text-xs text-warm-gray font-medium">Filter Harga:</span>
            <div class="flex items-center gap-2">
                <span class="text-xs text-warm-gray">Rp</span>
                <input type="number" x-model="priceMin" :min="{{ $minPrice }}" :max="priceMax"
                       class="w-24 border border-sand rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:border-sage-green">
                <span class="text-xs text-warm-gray">—</span>
                <span class="text-xs text-warm-gray">Rp</span>
                <input type="number" x-model="priceMax" :min="priceMin" :max="{{ $maxPrice }}"
                       class="w-24 border border-sand rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:border-sage-green">
            </div>
            <button @click="priceMin = {{ $minPrice }}; priceMax = {{ $maxPrice }}"
                    class="text-xs text-warm-gray hover:text-dark-oak underline">Reset</button>
            <span class="text-xs text-warm-gray ml-auto" x-text="filteredProducts.length + ' produk ditemukan'"></span>
        </div>
    </div>
</section>

<section class="py-12 bg-linen">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div x-show="filteredProducts.some(p => p.slug === '{{ $product['slug'] }}')"
                 x-data="{ openModal: false }">
                <!-- Card -->
                <div class="text-left w-full bg-white border border-amber-100 rounded-xl overflow-hidden group transition-all hover:shadow-md hover:-translate-y-1 block">
                    <div class="relative aspect-[4/3] overflow-hidden">
                        <img src="{{ $product->image_url }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        @if($product['badge'])<span class="absolute top-2 left-2 bg-sage-green text-white text-xs rounded-lg px-2 py-0.5">{{ $product['badge'] }}</span>@endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-dark-oak text-sm mb-1 line-clamp-1">{{ $product['name'] }}</h3>
                        <p class="text-xs text-warm-gray mb-1">{{ $product->category->name ?? '—' }}</p>
                        @if($product['price'])
                        <p class="text-sm font-semibold text-sage-green mb-3">Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
                        @else
                        <p class="text-sm text-warm-gray mb-3">Hubungi untuk harga</p>
                        @endif
                        <button @click="openModal = true" class="w-full bg-sage-green text-white rounded-lg py-2 text-xs text-center font-medium transition-colors hover:brightness-110">Lihat Detail</button>
                    </div>
                </div>

                <!-- Floating Modal -->
                <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" style="display: none;">
                    <div x-show="openModal"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="openModal = false"
                         class="absolute inset-0 bg-dark-oak/80 backdrop-blur-sm"></div>

                    <div x-show="openModal"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-8 sm:scale-95"
                         class="relative w-full max-w-3xl bg-white rounded-2xl overflow-hidden shadow-2xl flex flex-col md:flex-row max-h-[90vh]">

                        <button @click="openModal = false" class="absolute top-4 right-4 bg-white/80 backdrop-blur text-dark-oak hover:bg-dark-oak hover:text-white transition-colors z-10 w-8 h-8 flex justify-center items-center rounded-full text-xl leading-none">&times;</button>

                        <!-- Image Section -->
                        <div class="w-full md:w-1/2 h-64 md:h-auto bg-cream relative">
                            <img src="{{ $product->image_url }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover">
                            @if($product['badge'])
                            <span class="absolute top-4 left-4 bg-sage-green text-white text-xs rounded-lg px-3 py-1 shadow-sm">{{ $product['badge'] }}</span>
                            @endif
                        </div>

                        <!-- Info Section -->
                        <div class="w-full md:w-1/2 p-6 md:p-8 flex flex-col overflow-y-auto">
                            <p class="text-terracotta text-sm uppercase tracking-wide font-medium mb-2">{{ $product->category->name ?? '—' }}</p>
                            <h2 class="font-playfair text-3xl text-dark-oak mb-4">{{ $product['name'] }}</h2>

                            @if($product['price'])
                            <p class="font-playfair text-2xl text-sage-green mb-4">Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
                            @endif

                            <div class="w-12 h-px bg-amber-200 mb-4"></div>

                            <p class="text-warm-gray text-sm leading-relaxed mb-8 flex-1">
                                {{ $product['description'] ?: 'Rangkaian bunga segar dan rapi yang disusun secara eksklusif. Sangat cocok diberikan sebagai hadiah pada momen spesial.' }}
                            </p>

                            <div class="mt-auto flex flex-col gap-3">
                                @php $waNum = setting('whatsapp_link', '6285649150049'); @endphp
                                <a href="https://wa.me/{{ $waNum }}?text={{ urlencode('Halo! Saya tertarik untuk memesan buket: ' . $product['name']) }}" target="_blank" class="w-full bg-sage-green hover:brightness-110 text-white rounded-full py-3.5 font-medium flex items-center justify-center gap-2 transition-all">
                                    <span>Pesan via WhatsApp</span>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/></svg>
                                </a>
                                <button @click="openModal = false" class="w-full bg-transparent border border-amber-200 text-dark-oak hover:bg-cream rounded-full py-3 font-medium transition-all">Tutup Katalog</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div x-show="filteredProducts.length === 0" class="text-center py-16 text-warm-gray text-sm">
            Tidak ada produk yang cocok dengan filter yang dipilih.
        </div>
    </div>
</section>
</div>
@endsection
