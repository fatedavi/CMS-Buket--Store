@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="font-playfair text-2xl text-dark-oak">Produk</h1>
    <a href="{{ route('admin.products.create') }}" class="bg-sage-green text-white rounded-xl px-4 py-2 text-sm">Tambah Produk</a>
</div>

<div class="bg-white rounded-2xl border border-amber-100 overflow-hidden">
    <table class="w-full">
        <thead class="bg-cream">
            <tr>
                <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Gambar</th>
                <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Nama</th>
                <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Kategori</th>
                <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Status</th>
                <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Tanggal</th>
                <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr class="border-t border-amber-100 hover:bg-[#faf8f4]">
                <td class="px-4 py-3"><img src="{{ $product['image'] }}" class="w-12 h-12 rounded-lg object-cover"></td>
                <td class="px-4 py-3 text-sm text-dark-oak">{{ $product['name'] }}</td>
                <td class="px-4 py-3 text-sm text-warm-gray">{{ $product['category'] }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded text-xs {{ $product['status'] === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $product['status'] }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-warm-gray">{{ $product['date'] }}</td>
                <td class="px-4 py-3">
                    <button class="text-warm-gray hover:text-sage-green mr-2">✏️</button>
                    <button class="text-warm-gray hover:text-terracotta">🗑️</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection