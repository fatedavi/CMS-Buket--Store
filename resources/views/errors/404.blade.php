@extends('layouts.app')

@section('content')
<section class="py-24 bg-linen min-h-[70vh] flex items-center">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <p class="font-playfair text-8xl text-cream">404</p>
        <h1 class="font-playfair text-3xl text-dark-oak -mt-8">Halaman Tidak Ditemukan</h1>
        <p class="text-warm-gray mt-4">Sepertinya buket yang kamu cari sudah habis, atau alamatnya salah.</p>
        
        <div class="flex justify-center gap-4 mt-8">
            <a href="{{ route('home') }}" class="bg-sage-green text-white rounded-full px-6 py-3 font-medium">Kembali ke Beranda</a>
            <a href="{{ route('catalog.index') }}" class="border border-sand text-dark-oak rounded-full px-6 py-3 font-medium">Lihat Katalog</a>
        </div>
    </div>
</section>
@endsection