@extends('layouts.admin')

@section('content')
<div x-data="{
    search: '',
    filterStatus: 'Semua',
    filterCategory: 'Semua',
    items: {{ $products->map(fn($p) => [
        'name' => $p->name,
        'category' => $p->category,
        'status' => $p->status,
    ])->toJson() }},
    get filteredItems() {
        const s = this.search.toLowerCase();
        return this.items.filter(i =>
            (s === '' || i.name.toLowerCase().includes(s) || i.category.toLowerCase().includes(s))
            && (this.filterStatus === 'Semua' || i.status === this.filterStatus)
            && (this.filterCategory === 'Semua' || i.category === this.filterCategory)
        );
    }
}">
    <div class="flex justify-between items-center mb-6">
        <h1 class="font-playfair text-2xl text-dark-oak">Produk</h1>
        <a href="{{ route('admin.products.create') }}" class="bg-sage-green text-white rounded-xl px-4 py-2 text-sm hover:brightness-110 transition-all">Tambah Produk</a>
    </div>

    <div class="bg-white rounded-2xl border border-amber-100 p-4 mb-6 flex flex-wrap gap-3">
        <div class="flex-1 min-w-[200px]">
            <input type="text" x-model="search" placeholder="Cari produk..." class="w-full border border-amber-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-sage-green">
        </div>
        <select x-model="filterStatus" class="border border-amber-200 rounded-xl px-4 py-2 text-sm text-warm-gray focus:outline-none focus:border-sage-green">
            <option value="Semua">Semua Status</option>
            <option value="Aktif">Aktif</option>
            <option value="Draft">Draft</option>
        </select>
        <select x-model="filterCategory" class="border border-amber-200 rounded-xl px-4 py-2 text-sm text-warm-gray focus:outline-none focus:border-sage-green">
            <option value="Semua">Semua Kategori</option>
            @php $cats = $products->pluck('category')->unique()->sort(); @endphp
            @foreach($cats as $cat)
            <option value="{{ $cat }}">{{ $cat }}</option>
            @endforeach
        </select>
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
                <tr class="border-t border-amber-100 hover:bg-[#faf8f4]"
                    x-show="filteredItems.some(i => i.name === '{{ $product->name }}' && i.category === '{{ $product->category }}' && i.status === '{{ $product->status }}')">
                    <td class="px-4 py-3">
                        @php
                            $imgSrc = $product->image
                                ? (str_starts_with($product->image, 'http') ? $product->image : Storage::url($product->image))
                                : null;
                        @endphp
                        @if($imgSrc)
                        <img src="{{ $imgSrc }}" class="w-12 h-12 rounded-lg object-cover">
                        @else
                        <div class="w-12 h-12 rounded-lg bg-cream flex items-center justify-center">
                            <svg class="w-5 h-5 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        @endif
                    </td>
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
                <tr x-show="items.length > 0 && filteredItems.length === 0"
                    class="border-t border-amber-100">
                    <td colspan="6" class="px-4 py-8 text-center text-warm-gray text-sm">Tidak ada produk yang cocok dengan filter.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection