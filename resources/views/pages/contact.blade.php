@extends('layouts.app')

@section('content')
<section class="relative h-[280px] flex items-center">
    <div class="absolute inset-0 bg-dark-oak/60 bg-[url('https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=1920&q=80')] bg-cover bg-center"></div>
    <div class="absolute inset-0 bg-dark-oak/60"></div>
    <div class="relative max-w-7xl mx-auto px-4 w-full text-center">
        <p class="text-white/60 text-sm mb-2">Beranda / Kontak</p>
        <h1 class="font-playfair text-3xl text-white">Hubungi Kami</h1>
    </div>
</section>

<section class="py-16 bg-linen">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white border border-amber-100 rounded-2xl p-6">
                <h2 class="font-playfair text-xl text-dark-oak mb-6">Info Kontak</h2>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <span class="text-xl">📍</span>
                        <div>
                            <p class="font-medium text-dark-oak">Alamat</p>
                            <p class="text-warm-gray text-sm">{{ $contact['alamat'] }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <span class="text-xl">📱</span>
                        <div>
                            <p class="font-medium text-dark-oak">WhatsApp</p>
                            <a href="https://wa.me/{{ $contact['nomor_wa'] }}" class="text-sage-green text-sm hover:underline">{{ $contact['nomor_wa'] }}</a>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <span class="text-xl">🕐</span>
                        <div>
                            <p class="font-medium text-dark-oak">Jam Buka</p>
                            <p class="text-warm-gray text-sm">{{ $contact['jam_buka'] }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <span class="text-xl">📸</span>
                        <div>
                            <p class="font-medium text-dark-oak">Instagram</p>
                            <p class="text-warm-gray text-sm">{{ $contact['instagram'] }}</p>
                        </div>
                    </div>
                </div>
                
                <a href="https://wa.me/{{ $contact['nomor_wa'] }}" class="block w-full bg-sage-green text-white rounded-2xl py-4 text-center font-medium mt-6">Chat via WhatsApp</a>
                <a href="{{ $contact['maps_url'] }}" target="_blank" class="block w-full border border-sand text-dark-oak rounded-2xl py-3 text-center font-medium mt-3">Lihat di Google Maps</a>
            </div>
            
            <div class="rounded-2xl overflow-hidden h-[400px]">
                <iframe src="{{ $contact['maps_embed'] }}" width="100%" height="100%" style="border:0;" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</section>
@endsection