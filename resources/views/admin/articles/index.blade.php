@extends('layouts.admin')

@section('content')
<div x-data="{
    search: '',
    filterCategory: 'Semua',
    items: {{ $articles->map(fn($a) => [
        'title' => $a->title,
        'category' => $a->category,
    ])->toJson() }},
    get filteredItems() {
        const s = this.search.toLowerCase();
        return this.items.filter(i =>
            (s === '' || i.title.toLowerCase().includes(s) || i.category.toLowerCase().includes(s))
            && (this.filterCategory === 'Semua' || i.category === this.filterCategory)
        );
    }
}">
    <div class="flex justify-between items-center mb-6">
        <h1 class="font-playfair text-2xl text-dark-oak">Artikel</h1>
        <a href="{{ route('admin.articles.create') }}" class="bg-sage-green text-white rounded-xl px-4 py-2 text-sm hover:brightness-110 transition-all">Tambah Artikel</a>
    </div>

    <div class="bg-white rounded-2xl border border-amber-100 p-4 mb-6 flex flex-wrap gap-3">
        <div class="flex-1 min-w-[200px]">
            <input type="text" x-model="search" placeholder="Cari artikel..." class="w-full border border-amber-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-sage-green">
        </div>
        <select x-model="filterCategory" class="border border-amber-200 rounded-xl px-4 py-2 text-sm text-warm-gray focus:outline-none focus:border-sage-green">
            <option value="Semua">Semua Kategori</option>
            @php $cats = $articles->pluck('category')->unique()->sort(); @endphp
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
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Judul</th>
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Kategori</th>
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Tanggal</th>
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                <tr class="border-t border-amber-100 hover:bg-[#faf8f4]"
                    x-show="filteredItems.some(i => i.title === '{{ $article->title }}' && i.category === '{{ $article->category }}')">
                    <td class="px-4 py-3"><img src="{{ $article->image }}" class="w-12 h-12 rounded-lg object-cover"></td>
                    <td class="px-4 py-3 text-sm text-dark-oak">{{ $article->title }}</td>
                    <td class="px-4 py-3 text-sm text-warm-gray">{{ $article->category }}</td>
                    <td class="px-4 py-3 text-sm text-warm-gray">{{ $article->date }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.articles.edit', $article) }}" class="inline-block text-warm-gray hover:text-sage-green mr-2"><x-icons.pencil /></a>
                        <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="inline" onsubmit="return confirm('Hapus artikel ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-warm-gray hover:text-terracotta"><x-icons.trash /></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr class="border-t border-amber-100">
                    <td colspan="5" class="px-4 py-8 text-center text-warm-gray text-sm">Belum ada artikel. <a href="{{ route('admin.articles.create') }}" class="text-sage-green underline">Tambah artikel</a></td>
                </tr>
                @endforelse
                <tr x-show="items.length > 0 && filteredItems.length === 0"
                    class="border-t border-amber-100">
                    <td colspan="5" class="px-4 py-8 text-center text-warm-gray text-sm">Tidak ada artikel yang cocok dengan filter.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection