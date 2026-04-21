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

<section class="py-8 bg-linen border-b border-amber-100" x-data="{ activeCategory: 'Semua' }">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-wrap gap-2">
            @foreach($categories as $cat)
            <button @click="activeCategory = '{{ $cat }}'" 
                class="rounded-full px-4 py-1.5 text-sm border transition-all"
                :class="activeCategory === '{{ $cat }}' ? 'bg-sage-green text-white border-sage-green' : 'border-sand text-warm-gray hover:border-sage-green'">
                {{ $cat }}
            </button>
            @endforeach
        </div>
    </div>
</section>

<section class="py-12 bg-linen">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
            <a href="{{ route('catalog.show', $product['slug']) }}" class="bg-white border border-amber-100 rounded-xl overflow-hidden group">
                <div class="relative aspect-[4/3]">
                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover" loading="lazy">
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
</section>
@endsection