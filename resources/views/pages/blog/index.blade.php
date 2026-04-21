@extends('layouts.app')

@section('content')
<section class="relative h-[280px] flex items-center">
    <div class="absolute inset-0 bg-dark-oak/60 bg-[url('https://images.unsplash.com/photo-1490750967868-88df5691cc45?w=1920&q=80')] bg-cover bg-center"></div>
    <div class="absolute inset-0 bg-dark-oak/60"></div>
    <div class="relative max-w-7xl mx-auto px-4 w-full text-center">
        <p class="text-white/60 text-sm mb-2">Beranda / Blog</p>
        <h1 class="font-playfair text-3xl text-white">Blog & Inspirasi Bunga</h1>
    </div>
</section>

<section class="py-16 bg-linen">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($articles as $article)
            <a href="{{ route('blog.show', $article['slug']) }}" class="bg-white border border-amber-100 rounded-xl overflow-hidden group">
                <div class="aspect-[4/3] overflow-hidden">
                    <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform" loading="lazy">
                </div>
                <div class="p-4">
                    <span class="bg-cream text-warm-gray text-xs rounded-lg px-2 py-1">{{ $article['category'] }}</span>
                    <h3 class="font-medium text-dark-oak mt-2 mb-1">{{ $article['title'] }}</h3>
                    <p class="text-xs text-warm-gray">{{ $article['date'] }}</p>
                    <p class="text-sm text-warm-gray mt-2 line-clamp-2">{{ $article['excerpt'] }}</p>
                    <span class="text-sm text-sage-green mt-2 block">Baca selengkapnya →</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endsection