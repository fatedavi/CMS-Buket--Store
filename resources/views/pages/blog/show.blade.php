@extends('layouts.app')

@section('content')
<section class="py-8 bg-linen">
    <div class="max-w-7xl mx-auto px-4">
        <p class="text-warm-gray text-sm">Beranda / Blog / {{ $article['title'] }}</p>
    </div>
</section>

<article class="py-8 bg-linen">
    <div class="max-w-3xl mx-auto px-4">
        <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" class="w-full h-[400px] object-cover rounded-2xl mb-6">
        <span class="bg-cream text-warm-gray text-xs rounded-lg px-3 py-1">{{ $article['category'] }}</span>
        <span class="text-warm-gray text-sm ml-3">{{ $article['date'] }}</span>
        <h1 class="font-playfair text-3xl text-dark-oak mt-4">{{ $article['title'] }}</h1>
        
        <div class="prose prose-amber mt-8 text-warm-gray">
            {!! $article['content'] !!}
        </div>
        
        <div class="bg-cream rounded-2xl p-6 text-center mt-12">
            <p class="font-playfair text-xl text-dark-oak">Siap memesan buket bunga?</p>
            <p class="text-warm-gray text-sm mt-2">Konsultasikan kebutuhanmu langsung via WhatsApp</p>
            <a href="https://wa.me/6285649150049" class="inline-block mt-4 bg-sage-green text-white rounded-full px-6 py-3 font-medium">Hubungi via WhatsApp</a>
        </div>
    </div>
</article>

<section class="py-12 bg-linen">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="font-playfair text-xl text-dark-oak mb-4">Artikel Terkait</h2>
        <div class="grid md:grid-cols-3 gap-4">
            @foreach($related as $item)
            <a href="{{ route('blog.show', $item['slug']) }}" class="bg-white border border-amber-100 rounded-xl overflow-hidden flex">
                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-24 h-24 object-cover">
                <div class="p-3">
                    <h3 class="font-medium text-dark-oak text-sm">{{ $item['title'] }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endsection