@extends('layouts.admin')

@section('content')
<h1 class="font-playfair text-2xl text-dark-oak mb-6">Edit Produk</h1>

<div class="bg-white rounded-2xl border border-amber-100 p-6">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}"
                   class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
            @error('name')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $product->slug) }}"
                   class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
            <p class="text-xs text-warm-gray mt-1">Kosongkan untuk membuat otomatis dari nama produk</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Kategori</label>
            <select name="category" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                @foreach(['Wisuda','Anniversary','Ulang Tahun','Wedding','Custom'] as $cat)
                <option value="{{ $cat }}" {{ old('category', $product->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            @error('category')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Deskripsi</label>
            <textarea name="description" rows="4"
                      class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">{{ old('description', $product->description) }}</textarea>
        </div>

        {{-- Upload Gambar --}}
        <div>
            <label class="block text-sm font-medium text-dark-oak mb-2">Gambar Produk</label>

            {{-- Gambar saat ini --}}
            @php
                $currentImg = $product->image
                    ? (str_starts_with($product->image, 'http') ? $product->image : Storage::url($product->image))
                    : null;
            @endphp

            @if($currentImg)
            <div id="current-image-wrap" class="mb-3 flex items-center gap-4 p-3 bg-cream rounded-xl border border-amber-100">
                <img src="{{ $currentImg }}" alt="Gambar saat ini" class="w-20 h-20 object-cover rounded-xl shadow-sm flex-shrink-0">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-dark-oak">Gambar saat ini</p>
                    <p class="text-[11px] text-warm-gray mt-0.5 truncate">{{ $product->image }}</p>
                    <label class="flex items-center gap-1.5 mt-2 cursor-pointer">
                        <input type="checkbox" name="remove_image" value="1" id="remove-cb"
                               class="rounded border-sand text-terracotta"
                               onchange="toggleRemove(this)">
                        <span class="text-xs text-terracotta">Hapus gambar ini</span>
                    </label>
                </div>
            </div>
            @endif

            {{-- Drop zone --}}
            <div id="drop-zone"
                 class="relative border-2 border-dashed border-sand rounded-2xl p-6 text-center cursor-pointer hover:border-sage-green hover:bg-sage-green/5 transition-all group {{ $currentImg ? '' : '' }}"
                 onclick="document.getElementById('image-input').click()">

                <div id="upload-placeholder">
                    <div class="w-12 h-12 rounded-full bg-cream flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-dark-oak">
                        {{ $currentImg ? 'Pilih gambar baru untuk mengganti' : 'Klik atau drag & drop gambar di sini' }}
                    </p>
                    <p class="text-xs text-warm-gray mt-1">PNG, JPG, WEBP — Maks. 2MB</p>
                </div>

                <div id="image-preview" class="hidden">
                    <img id="preview-img" src="" alt="Preview" class="mx-auto max-h-48 rounded-xl object-contain shadow">
                    <p id="preview-name" class="text-xs text-warm-gray mt-2 truncate"></p>
                    <button type="button" onclick="clearImage(event)"
                            class="mt-2 text-xs text-terracotta hover:underline">Batal pilih gambar baru</button>
                </div>

                <input type="file" id="image-input" name="image" accept="image/*" class="hidden" onchange="previewImage(event)">
            </div>
            @error('image')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Badge</label>
            <select name="badge" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                <option value="">Tidak ada</option>
                @foreach(['Bestseller','Baru','Populer'] as $b)
                <option value="{{ $b }}" {{ old('badge', $product->badge) === $b ? 'selected' : '' }}>{{ $b }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Status</label>
            <select name="status" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                <option value="Aktif" {{ old('status', $product->status) === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Draft" {{ old('status', $product->status) === 'Draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-sage-green text-white rounded-xl px-6 py-2.5 font-medium hover:brightness-110 transition-all">Simpan Perubahan</button>
            <a href="{{ route('admin.products') }}" class="border border-sand text-dark-oak rounded-xl px-6 py-2.5 font-medium hover:bg-cream transition-all">Batal</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
const dropZone = document.getElementById('drop-zone');
dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.classList.add('border-sage-green','bg-sage-green/5'); });
dropZone.addEventListener('dragleave', () => { dropZone.classList.remove('border-sage-green','bg-sage-green/5'); });
dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-sage-green','bg-sage-green/5');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('image-input').files = dt.files;
        showPreview(file);
    }
});

function previewImage(e) {
    const file = e.target.files[0];
    if (file) showPreview(file);
}

function showPreview(file) {
    const reader = new FileReader();
    reader.onload = (e) => {
        document.getElementById('preview-img').src = e.target.result;
        document.getElementById('preview-name').textContent = file.name;
        document.getElementById('upload-placeholder').classList.add('hidden');
        document.getElementById('image-preview').classList.remove('hidden');
        // Uncheck remove checkbox kalau pilih gambar baru
        const cb = document.getElementById('remove-cb');
        if (cb) cb.checked = false;
    };
    reader.readAsDataURL(file);
}

function clearImage(e) {
    e.stopPropagation();
    document.getElementById('image-input').value = '';
    document.getElementById('upload-placeholder').classList.remove('hidden');
    document.getElementById('image-preview').classList.add('hidden');
}

function toggleRemove(cb) {
    // Kalau centang hapus, clear file input juga
    if (cb.checked) {
        document.getElementById('image-input').value = '';
        document.getElementById('upload-placeholder').classList.remove('hidden');
        document.getElementById('image-preview').classList.add('hidden');
    }
}
</script>
@endpush
@endsection
