@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="font-playfair text-2xl text-dark-oak">Dashboard</h1>
    <p class="text-sm text-warm-gray mt-1">Selamat datang di panel admin {{ setting('store_name', 'Toko Bunga') }}</p>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-5 mb-8">
    <div class="bg-white rounded-2xl border border-amber-100 p-5 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-sage-green/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-sage-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4l-8 4"/></svg>
            </div>
            <span class="text-[11px] text-warm-gray uppercase tracking-wider font-medium">Produk</span>
        </div>
        <span class="font-playfair text-3xl text-dark-oak block">{{ $stats['total_products'] }}</span>
    </div>
    <div class="bg-white rounded-2xl border border-amber-100 p-5 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-blush/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <span class="text-[11px] text-warm-gray uppercase tracking-wider font-medium">Artikel</span>
        </div>
        <span class="font-playfair text-3xl text-dark-oak block">{{ $stats['total_articles'] }}</span>
    </div>
    <div class="bg-white rounded-2xl border border-amber-100 p-5 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <span class="text-[11px] text-warm-gray uppercase tracking-wider font-medium">Kategori</span>
        </div>
        <span class="font-playfair text-3xl text-dark-oak block">{{ $stats['total_categories'] }}</span>
    </div>
    <div class="bg-white rounded-2xl border border-amber-100 p-5 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <span class="text-[11px] text-warm-gray uppercase tracking-wider font-medium">Chat Bulan Ini</span>
        </div>
        <span class="font-playfair text-3xl text-dark-oak block">{{ $stats['monthly_chats'] }}</span>
    </div>
</div>

<div class="bg-white rounded-2xl border border-amber-100 overflow-hidden shadow-sm">
    <div class="px-5 py-4 border-b border-amber-100 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <div class="w-2 h-2 rounded-full bg-sage-green"></div>
            <h2 class="font-playfair text-base text-dark-oak">Produk Terbaru</h2>
        </div>
        <a href="{{ route('admin.products') }}" class="text-xs text-sage-green hover:text-sage-green/80 font-medium transition-colors">Lihat semua &rarr;</a>
    </div>

    {{-- Mobile Cards --}}
    <div class="block md:hidden p-4 space-y-3">
        @foreach($recentProducts as $product)
        @php $imgSrc = $product->image ? (str_starts_with($product->image, 'http') ? $product->image : Storage::url($product->image)) : null; @endphp
        <div class="flex items-center gap-3 bg-cream/30 rounded-xl p-3 border border-amber-100">
            <div class="w-12 h-12 rounded-lg bg-white overflow-hidden flex-shrink-0 border border-amber-100">
                @if($imgSrc)
                <img src="{{ $imgSrc }}" class="w-full h-full object-contain p-1">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-dark-oak truncate">{{ $product->name }}</p>
                <p class="text-xs text-warm-gray mt-0.5">{{ $product->category->name ?? '—' }}</p>
            </div>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium flex-shrink-0 {{ $product->status === 'Aktif' ? 'bg-sage-green/10 text-sage-green' : 'bg-amber-100 text-amber-700' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $product->status === 'Aktif' ? 'bg-sage-green' : 'bg-amber-500' }}"></span>
                {{ $product->status }}
            </span>
        </div>
        @endforeach
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-cream/50">
                    <th class="px-5 py-3 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Gambar</th>
                    <th class="px-5 py-3 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Nama</th>
                    <th class="px-5 py-3 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Kategori</th>
                    <th class="px-5 py-3 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentProducts as $product)
                <tr class="border-t border-amber-100/60 hover:bg-cream/30 transition-colors">
                    <td class="px-5 py-3.5">
                        @php $imgSrc = $product->image ? (str_starts_with($product->image, 'http') ? $product->image : Storage::url($product->image)) : null; @endphp
                        @if($imgSrc)
                        <div class="w-10 h-10 rounded-lg bg-cream overflow-hidden"><img src="{{ $imgSrc }}" class="w-full h-full object-contain p-0.5"></div>
                        @else
                        <div class="w-10 h-10 rounded-lg bg-cream"></div>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-sm text-dark-oak font-medium">{{ $product->name }}</td>
                    <td class="px-5 py-3.5 text-sm text-warm-gray">{{ $product->category->name ?? '—' }}</td>
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-medium {{ $product->status === 'Aktif' ? 'bg-sage-green/10 text-sage-green' : 'bg-amber-100 text-amber-700' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $product->status === 'Aktif' ? 'bg-sage-green' : 'bg-amber-500' }}"></span>
                            {{ $product->status }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection