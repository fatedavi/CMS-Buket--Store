@extends('layouts.admin')

@section('content')
<div x-data="{
    search: '',
    filterActive: 'Semua',
    items: {{ $tips->map(fn($t) => [
        'title' => $t->title,
        'is_active' => $t->is_active,
    ])->toJson() }},
    get filteredItems() {
        const s = this.search.toLowerCase();
        return this.items.filter(i =>
            (s === '' || i.title.toLowerCase().includes(s))
            && (this.filterActive === 'Semua'
                || (this.filterActive === 'Aktif' && i.is_active)
                || (this.filterActive === 'Nonaktif' && !i.is_active))
        );
    }
}">
    <div class="flex justify-between items-center mb-6">
        <h1 class="font-playfair text-2xl text-dark-oak">Tips & Funfact</h1>
        <a href="{{ route('admin.tips.create') }}" class="bg-sage-green text-white rounded-xl px-4 py-2 text-sm hover:brightness-110 transition-all">Tambah Tips</a>
    </div>

    <div class="bg-white rounded-2xl border border-amber-100 p-4 mb-6 flex flex-wrap gap-3">
        <div class="flex-1 min-w-[200px]">
            <input type="text" x-model="search" placeholder="Cari tips..." class="w-full border border-amber-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-sage-green">
        </div>
        <select x-model="filterActive" class="border border-amber-200 rounded-xl px-4 py-2 text-sm text-warm-gray focus:outline-none focus:border-sage-green">
            <option value="Semua">Semua Status</option>
            <option value="Aktif">Aktif</option>
            <option value="Nonaktif">Nonaktif</option>
        </select>
    </div>

    <div class="bg-white rounded-2xl border border-amber-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-cream">
                <tr>
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Gambar</th>
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Icon</th>
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Judul</th>
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Konten</th>
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Urutan</th>
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Status</th>
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tips as $tip)
                <tr class="border-t border-amber-100 hover:bg-[#faf8f4]"
                    x-show="filteredItems.some(i => i.title === '{{ $tip->title }}')">
                    <td class="px-4 py-3">
                        @if($tip->background_image_url)
                        <img src="{{ $tip->background_image_url }}" class="w-20 h-12 rounded-lg object-cover border border-amber-100">
                        @else
                        <div class="w-20 h-12 rounded-lg bg-cream border border-amber-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        @endif
                    </td>
                    <td class="px-4 py-3"><x-icons.tip :icon="$tip->icon ?? 'lightbulb'" class="w-6 h-6 text-dark-oak" /></td>
                    <td class="px-4 py-3 text-sm text-dark-oak">{{ $tip->title }}</td>
                    <td class="px-4 py-3 text-sm text-warm-gray max-w-xs truncate">{{ Str::limit($tip->content, 80) }}</td>
                    <td class="px-4 py-3 text-sm text-warm-gray">{{ $tip->order }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs {{ $tip->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $tip->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.tips.edit', $tip) }}" class="inline-block text-warm-gray hover:text-sage-green mr-2"><x-icons.pencil /></a>
                        <form action="{{ route('admin.tips.destroy', $tip) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tips ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-warm-gray hover:text-terracotta"><x-icons.trash /></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr class="border-t border-amber-100">
                    <td colspan="7" class="px-4 py-8 text-center text-warm-gray text-sm">Belum ada tips. <a href="{{ route('admin.tips.create') }}" class="text-sage-green underline">Tambah tips</a></td>
                </tr>
                @endforelse
                <tr x-show="items.length > 0 && filteredItems.length === 0"
                    class="border-t border-amber-100">
                    <td colspan="7" class="px-4 py-8 text-center text-warm-gray text-sm">Tidak ada tips yang cocok dengan filter.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection