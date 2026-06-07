@extends('layouts.admin')

@section('content')
<h1 class="font-playfair text-2xl text-dark-oak mb-6">Edit Tips</h1>

<div class="bg-white rounded-2xl border border-amber-100 p-6">
    <form action="{{ route('admin.tips.update', $tip) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div x-data="{ selectedIcon: '{{ old('icon', $tip->icon) }}' }">
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
            <input type="text" name="title" value="{{ old('title', $tip->title) }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
            @error('title')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Konten</label>
            <textarea name="content" rows="6" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">{{ old('content', $tip->content) }}</textarea>
            @error('content')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-2">Gambar Background</label>

            @php $currentBg = $tip->background_image_url; @endphp

            @if($currentBg)
            <div id="current-bg-wrap" class="mb-3 flex items-center gap-4 p-3 bg-cream rounded-xl border border-amber-100">
                <img src="{{ $currentBg }}" alt="Bg saat ini" class="w-24 h-16 object-cover rounded-xl shadow-sm flex-shrink-0">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-dark-oak">Gambar saat ini</p>
                    <label class="flex items-center gap-1.5 mt-2 cursor-pointer">
                        <input type="checkbox" name="remove_background_image" value="1"
                               class="rounded border-sand text-terracotta">
                        <span class="text-xs text-terracotta">Hapus gambar ini</span>
                    </label>
                </div>
            </div>
            @endif

            <div id="drop-zone"
                 class="relative border-2 border-dashed border-sand rounded-2xl p-6 text-center cursor-pointer hover:border-sage-green hover:bg-sage-green/5 transition-all group"
                 onclick="document.getElementById('bg-image-input').click()">

                <div id="upload-placeholder">
                    <div class="w-12 h-12 rounded-full bg-cream flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-dark-oak">{{ $currentBg ? 'Pilih gambar baru untuk mengganti' : 'Klik atau drag & drop gambar di sini' }}</p>
                    <p class="text-xs text-warm-gray mt-1">PNG, JPG, WEBP — Maks. 2MB</p>
                </div>

                <div id="image-preview" class="hidden">
                    <img id="preview-img" src="" alt="Preview" class="mx-auto max-h-48 rounded-xl object-contain shadow">
                    <p id="preview-name" class="text-xs text-warm-gray mt-2 truncate"></p>
                    <button type="button" onclick="clearBgImage(event)"
                            class="mt-2 text-xs text-terracotta hover:underline">Batal pilih gambar baru</button>
                </div>

                <input type="file" id="bg-image-input" name="background_image" accept="image/*" class="hidden" onchange="previewBgImage(event)">
            </div>
            @error('background_image')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-dark-oak mb-1">Urutan</label>
                <input type="number" name="order" value="{{ old('order', $tip->order) }}" min="0" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-dark-oak mb-1">Status</label>
                <label class="flex items-center gap-2 mt-2.5">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $tip->is_active) ? 'checked' : '' }} class="rounded border-sand text-sage-green focus:ring-sage-green">
                    <span class="text-sm text-dark-oak">Aktif</span>
                </label>
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="bg-sage-green text-white rounded-xl px-6 py-2.5 font-medium hover:brightness-110 transition-all">Simpan Perubahan</button>
            <a href="{{ route('admin.tips') }}" class="border border-sand text-dark-oak rounded-xl px-6 py-2.5 font-medium">Batal</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
const bgDropZone = document.getElementById('drop-zone');
bgDropZone.addEventListener('dragover', (e) => { e.preventDefault(); bgDropZone.classList.add('border-sage-green','bg-sage-green/5'); });
bgDropZone.addEventListener('dragleave', () => { bgDropZone.classList.remove('border-sage-green','bg-sage-green/5'); });
bgDropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    bgDropZone.classList.remove('border-sage-green','bg-sage-green/5');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('bg-image-input').files = dt.files;
        showBgPreview(file);
    }
});

function previewBgImage(e) {
    const file = e.target.files[0];
    if (file) showBgPreview(file);
}

function showBgPreview(file) {
    const reader = new FileReader();
    reader.onload = (e) => {
        document.getElementById('preview-img').src = e.target.result;
        document.getElementById('preview-name').textContent = file.name;
        document.getElementById('upload-placeholder').classList.add('hidden');
        document.getElementById('image-preview').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}

function clearBgImage(e) {
    e.stopPropagation();
    document.getElementById('bg-image-input').value = '';
    document.getElementById('upload-placeholder').classList.remove('hidden');
    document.getElementById('image-preview').classList.add('hidden');
}
</script>
@endpush
@endsection