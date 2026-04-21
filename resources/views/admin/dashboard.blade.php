@extends('layouts.admin')

@section('content')
<h1 class="font-playfair text-2xl text-dark-oak mb-8">Dashboard</h1>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-amber-100 p-5">
        <span class="font-playfair text-2xl text-dark-oak block">{{ $stats['total_products'] }}</span>
        <span class="text-xs text-warm-gray">Total Produk</span>
    </div>
    <div class="bg-white rounded-2xl border border-amber-100 p-5">
        <span class="font-playfair text-2xl text-dark-oak block">{{ $stats['total_articles'] }}</span>
        <span class="text-xs text-warm-gray">Total Artikel</span>
    </div>
    <div class="bg-white rounded-2xl border border-amber-100 p-5">
        <span class="font-playfair text-2xl text-dark-oak block">{{ $stats['total_categories'] }}</span>
        <span class="text-xs text-warm-gray">Kategori Aktif</span>
    </div>
    <div class="bg-white rounded-2xl border border-amber-100 p-5">
        <span class="font-playfair text-2xl text-dark-oak block">{{ $stats['monthly_orders'] }}</span>
        <span class="text-xs text-warm-gray">Pesanan WA Bulan Ini</span>
    </div>
</div>

<div class="bg-white rounded-2xl border border-amber-100 overflow-hidden">
    <div class="bg-cream px-4 py-3 border-b border-amber-100">
        <h2 class="font-medium text-dark-oak text-sm">Produk Terbaru</h2>
    </div>
    <table class="w-full">
        <thead class="bg-[#faf8f4]">
            <tr>
                <th class="px-4 py-2 text-left text-sm text-dark-oak font-medium">Gambar</th>
                <th class="px-4 py-2 text-left text-sm text-dark-oak font-medium">Nama</th>
                <th class="px-4 py-2 text-left text-sm text-dark-oak font-medium">Kategori</th>
                <th class="px-4 py-2 text-left text-sm text-dark-oak font-medium">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentProducts as $product)
            <tr class="border-t border-amber-100 hover:bg-[#faf8f4]">
                <td class="px-4 py-3"><img src="{{ $product['image'] }}" class="w-10 h-10 rounded object-cover"></td>
                <td class="px-4 py-3 text-sm text-dark-oak">{{ $product['name'] }}</td>
                <td class="px-4 py-3 text-sm text-warm-gray">{{ $product['category'] }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded text-xs {{ $product['status'] === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $product['status'] }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection