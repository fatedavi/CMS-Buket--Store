@extends('layouts.admin')

@section('content')
<h1 class="font-playfair text-2xl text-dark-oak mb-6">Tambah Tips Baru</h1>

<div class="bg-white rounded-2xl border border-amber-100 p-6">
    <form action="{{ route('admin.tips.store') }}" method="POST" class="space-y-4">
        @csrf

        <div x-data="{ selectedIcon: '{{ old('icon', 'lightbulb') }}' }">
            <label class="block text-sm font-medium text-dark-oak mb-1">Icon</label>
            <div class="grid grid-cols-7 sm:grid-cols-9 md:grid-cols-13 gap-2 mb-1">
                @php $icons = \App\Models\Tip::iconOptions(); @endphp
                @foreach($icons as $key)
                <button type="button" @click="selectedIcon = '{{ $key }}'"
                    class="w-10 h-10 rounded-lg border-2 flex items-center justify-center transition-all"
                    :class="selectedIcon === '{{ $key }}' ? 'border-sage-green bg-sage-green/10 ring-2 ring-sage-green/30' : 'border-sand hover:border-sage-green hover:bg-sage-green/5'"
                    title="{{ $key }}">
                    <x-icons.tip :icon="$key" class="w-5 h-5 text-dark-oak" />
                </button>
                @endforeach
            </div>
            <input type="hidden" name="icon" x-model="selectedIcon">
            @error('icon')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Judul Tips</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
            @error('title')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Konten</label>
            <textarea name="content" rows="6" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">{{ old('content') }}</textarea>
            @error('content')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-dark-oak mb-1">Urutan</label>
                <input type="number" name="order" value="{{ old('order', 0) }}" min="0" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-dark-oak mb-1">Status</label>
                <label class="flex items-center gap-2 mt-2.5">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-sand text-sage-green focus:ring-sage-green">
                    <span class="text-sm text-dark-oak">Aktif</span>
                </label>
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="bg-sage-green text-white rounded-xl px-6 py-2.5 font-medium hover:brightness-110 transition-all">Simpan Tips</button>
            <a href="{{ route('admin.tips') }}" class="border border-sand text-dark-oak rounded-xl px-6 py-2.5 font-medium">Batal</a>
        </div>
    </form>
</div>
@endsection