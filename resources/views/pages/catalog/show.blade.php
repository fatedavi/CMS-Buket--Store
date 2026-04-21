@extends('layouts.app')

@section('content')
<section class="py-8 bg-linen">
    <div class="max-w-7xl mx-auto px-4">
        <p class="text-warm-gray text-sm">Beranda / Katalog / {{ $product['name'] }}</p>
    </div>
</section>

<section class="py-8 bg-linen">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-8">
            <div x-data="{ active: 0 }">
                <div class="aspect-square rounded-2xl overflow-hidden border border-amber-100 mb-4">
                    <img :src="$product['images'][active]" class="w-full h-full object-cover" :alt="$product['name']">
                </div>
                <div class="flex gap-2">
                    @foreach($product['images'] as $idx => $img)
                    <button @click="active = {{ $idx }}" class="w-20 h-20 rounded-xl overflow-hidden border-2" :class="active === {{ $idx }} ? 'border-sage-green' : 'border-transparent'">
                        <img src="{{ $img }}" class="w-full h-full object-cover">
                    </button>
                    @endforeach
                </div>
            </div>
            
            <div>
                <span class="bg-cream text-warm-gray rounded-lg px-3 py-1 text-xs">{{ $product['category'] }}</span>
                <h1 class="font-playfair text-2xl text-dark-oak mt-4">{{ $product['name'] }}</h1>
                <div class="border-t border-amber-100 my-4"></div>
                <p class="text-warm-gray text-sm leading-relaxed mb-4">{{ $product['description'] }}</p>
                <div class="border-t border-amber-100 my-4"></div>
                <p class="text-sm font-medium text-dark-oak mb-2">Tersedia untuk:</p>
                <div class="flex flex-wrap gap-2 mb-6">
                    @foreach(['Wisuda','Anniversary','Ulang Tahun','Wedding','Custom'] as $tag)
                    <span class="bg-cream text-warm-gray rounded-full px-3 py-1 text-xs">{{ $tag }}</span>
                    @endforeach
                </div>
                <a href="https://wa.me/{{ $product['whatsapp'] }}?text=Halo, saya tertarik dengan {{ $product['name'] }}. Apakah tersedia?" class="block w-full bg-sage-green text-white rounded-2xl py-4 text-center font-medium hover:brightness-110 transition-all">
                    Tanya & Pesan via WhatsApp
                </a>
                <div class="mt-6 space-y-2 text-sm text-warm-gray">
                    <p>🌿 Bunga segar, dirangkai saat hari pemesanan</p>
                    <p>🚚 Pengiriman area Sidoarjo & sekitarnya</p>
                    <p>💬 Konsultasi gratis sebelum pesan</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-12 bg-cream">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="font-playfair text-2xl text-dark-oak mb-6">Produk Lainnya</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $product)
            <a href="{{ route('catalog.show', $product['slug']) }}" class="bg-white border border-amber-100 rounded-xl overflow-hidden">
                <div class="aspect-[4/3]">
                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover">
                </div>
                <div class="p-3">
                    <h3 class="font-medium text-dark-oak text-sm">{{ $product['name'] }}</h3>
                    <p class="text-xs text-warm-gray">{{ $product['category'] }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endsection