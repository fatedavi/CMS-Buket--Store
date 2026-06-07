@extends('layouts.admin')

@section('content')
<div x-data="{
    search: '',
    items: {{ $categories->map(fn($c) => ['name' => $c->name, 'is_active' => $c->is_active])->toJson() }},
    get filteredItems() {
        const s = this.search.toLowerCase();
        return this.items.filter(i => s === '' || i.name.toLowerCase().includes(s));
    }
}">
    <div class="flex justify-between items-center mb-6">
        <h1 class="font-playfair text-2xl text-dark-oak">Kategori</h1>
        <a href="{{ route('admin.categories.create') }}" class="bg-sage-green text-white rounded-xl px-4 py-2 text-sm hover:brightness-110 transition-all">Tambah Kategori</a>
    </div>

    <div class="bg-white rounded-2xl border border-amber-100 p-4 mb-6">
        <input type="text" x-model="search" placeholder="Cari kategori..." class="w-full border border-amber-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-sage-green">
    </div>

    <div class="bg-white rounded-2xl border border-amber-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-cream">
                <tr>
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Gambar</th>
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Nama</th>
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Slug</th>
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Status</th>
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr class="border-t border-amber-100 hover:bg-[#faf8f4]"
                    x-show="filteredItems.some(i => i.name === '{{ $category->name }}')">
                    <td class="px-4 py-3">
                        @if($category->image)
                        <img src="{{ $category->image_url }}" class="w-12 h-12 rounded-lg object-cover border border-amber-100">
                        @else
                        <div class="w-12 h-12 rounded-lg bg-cream border border-amber-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-dark-oak font-medium">{{ $category->name }}</td>
                    <td class="px-4 py-3 text-sm text-warm-gray">{{ $category->slug }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="inline-block text-warm-gray hover:text-sage-green mr-2"><x-icons.pencil /></a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini? Semua produk dalam kategori ini akan menjadi uncategorized.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-warm-gray hover:text-terracotta"><x-icons.trash /></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr class="border-t border-amber-100">
                    <td colspan="5" class="px-4 py-8 text-center text-warm-gray text-sm">Belum ada kategori. <a href="{{ route('admin.categories.create') }}" class="text-sage-green underline">Tambah kategori</a></td>
                </tr>
                @endforelse
                <tr x-show="items.length > 0 && filteredItems.length === 0"
                    class="border-t border-amber-100">
                    <td colspan="5" class="px-4 py-8 text-center text-warm-gray text-sm">Tidak ada kategori yang cocok.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
