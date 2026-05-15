@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="font-playfair text-2xl text-dark-oak">Produk</h1>
    <a href="{{ route('admin.products.create') }}" class="bg-sage-green text-white rounded-xl px-4 py-2 text-sm hover:brightness-110 transition-all">Tambah Produk</a>
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
            @forelse($products as $product)
            <tr class="border-t border-amber-100 hover:bg-[#faf8f4]">
                <td class="px-4 py-3"><img src="{{ $product->image }}" class="w-12 h-12 rounded-lg object-cover"></td>
                <td class="px-4 py-3 text-sm text-dark-oak">{{ $product->name }}</td>
                <td class="px-4 py-3 text-sm text-warm-gray">{{ $product->category }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded text-xs {{ $product->status === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $product->status }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-warm-gray">{{ $product->created_at->format('j F Y') }}</td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.products.edit', $product) }}" class="inline-block text-warm-gray hover:text-sage-green mr-2"><x-icons.pencil /></a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-warm-gray hover:text-terracotta"><x-icons.trash /></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr class="border-t border-amber-100">
                <td colspan="6" class="px-4 py-8 text-center text-warm-gray text-sm">Belum ada produk. <a href="{{ route('admin.products.create') }}" class="text-sage-green underline">Tambah produk</a></td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
