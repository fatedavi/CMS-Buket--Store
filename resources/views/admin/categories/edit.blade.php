@extends('layouts.admin')

@section('content')
<div x-data="categoryEditPreview()" class="mb-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="font-playfair text-2xl text-dark-oak">Edit Kategori</h1>
        <label class="flex items-center gap-2 cursor-pointer select-none">
            <span class="text-sm text-warm-gray">Preview</span>
            <div class="relative">
                <input type="checkbox" x-model="showPreview" class="sr-only peer">
                <div class="w-10 h-5 bg-sand rounded-full peer peer-checked:bg-sage-green transition-colors"></div>
                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
            </div>
        </label>
    </div>

    <div class="grid grid-cols-1 gap-6" :class="showPreview ? 'lg:grid-cols-5' : ''">
        <div :class="showPreview ? 'lg:col-span-3' : ''">
            <div class="bg-white rounded-2xl border border-amber-100 p-6">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" id="form-category-edit-{{ $category->id }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-dark-oak mb-1">Nama Kategori</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" x-model="form.name"
                               class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                        @error('name')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-oak mb-1">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $category->slug) }}"
                               class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                        <p class="text-xs text-warm-gray mt-1">Kosongkan untuk membuat otomatis dari nama kategori</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-oak mb-2">Gambar Kategori</label>
                        <div id="drop-zone"
                             class="relative border-2 border-dashed border-sand rounded-2xl p-6 text-center cursor-pointer hover:border-sage-green hover:bg-sage-green/5 transition-all group"
                             onclick="document.getElementById('image-input').click()">

                            <div id="upload-placeholder" :class="form.imagePreview || form.currentImageUrl ? 'hidden' : ''">
                                <div class="w-12 h-12 rounded-full bg-cream flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-dark-oak">Klik atau drag & drop gambar di sini</p>
                                <p class="text-xs text-warm-gray mt-1">PNG, JPG, WEBP — Otomatis dikompres</p>
                            </div>

                            <div id="image-preview" :class="form.imagePreview || form.currentImageUrl ? '' : 'hidden'">
                                <img id="preview-img" :src="form.imagePreview || form.currentImageUrl" alt="Preview" class="mx-auto max-h-48 rounded-xl object-contain shadow">
                                <p id="preview-name" class="text-xs text-warm-gray mt-2 truncate" x-text="form.imageName || 'Gambar saat ini'"></p>
                                <button type="button" onclick="clearImage(event)"
                                        class="mt-2 text-xs text-terracotta hover:underline">Hapus gambar</button>
                            </div>

                            <input type="file" id="image-input" name="image" accept="image/*" class="hidden" @change="handleImageUpload">
                        </div>
                        @if($category->image)
                        <label class="flex items-center gap-2 mt-2">
                            <input type="checkbox" name="remove_image" value="1" class="rounded border-sand text-terracotta focus:ring-terracotta">
                            <span class="text-xs text-terracotta">Hapus gambar saat ini</span>
                        </label>
                        @endif
                        @error('image')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="rounded border-sand text-sage-green focus:ring-sage-green">
                            <span class="text-sm text-dark-oak font-medium">Aktif</span>
                        </label>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click.prevent="openConfirm('save', 'Simpan Perubahan', 'Simpan perubahan kategori?', '', 'form-category-edit-{{ $category->id }}')" class="bg-sage-green text-white rounded-xl px-6 py-2.5 font-medium hover:brightness-110 transition-all">Simpan Perubahan</button>
                        <a href="{{ route('admin.categories') }}" class="border border-sand text-dark-oak rounded-xl px-6 py-2.5 font-medium">Batal</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- PREVIEW PANEL --}}
        <div x-show="showPreview" class="lg:col-span-2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
            <div class="sticky top-8">
                <div class="bg-white rounded-2xl border border-amber-100 p-4 mb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-playfair text-base text-dark-oak">Preview Kategori</h3>
                        <span class="text-[10px] px-2 py-0.5 rounded-full" :class="form.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'" x-text="form.is_active ? 'Aktif' : 'Nonaktif'"></span>
                    </div>

                    <div class="group relative h-40 rounded-xl overflow-hidden max-w-sm mx-auto">
                        <template x-if="form.imagePreview || form.currentImageUrl">
                            <img :src="form.imagePreview || form.currentImageUrl" alt="" class="w-full h-full object-contain p-2 group-hover:scale-110 transition-transform duration-700">
                        </template>
                        <template x-if="!(form.imagePreview || form.currentImageUrl)">
                            <div class="w-full h-full bg-gradient-to-br from-sage-green/30 to-cream flex items-center justify-center">
                                <svg class="w-12 h-12 text-sand/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        </template>
                        <div class="absolute inset-0 bg-gradient-to-t from-dark-oak/80 via-dark-oak/20 to-transparent"></div>
                        <div class="absolute bottom-4 left-4">
                            <h3 class="text-white font-playfair text-base" x-text="form.name || 'Nama Kategori'"></h3>
                            <p class="text-white/70 text-xs mt-0.5">0 produk</p>
                        </div>
                        <div class="absolute inset-0 border-2 border-transparent group-hover:border-sage-green/50 rounded-xl transition-all duration-500"></div>
                        <div x-show="!form.is_active" class="absolute inset-0 bg-dark-oak/50 flex items-center justify-center rounded-xl">
                            <span class="bg-white/90 text-dark-oak text-xs font-medium px-3 py-1.5 rounded-full">Nonaktif</span>
                        </div>
                    </div>

                    <p class="text-[11px] text-warm-gray text-center mt-4">Tampilan pada halaman homepage</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function categoryEditPreview() {
    return {
        showPreview: false,
        form: {
            name: @js(old('name', $category->name)),
            is_active: {{ old('is_active', $category->is_active) ? 'true' : 'false' }},
            imagePreview: null,
            imageName: '',
            currentImageUrl: @js($category->image_url),
        },
        handleImageUpload(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (ev) => {
                this.form.imagePreview = ev.target.result;
                this.form.imageName = file.name;
                document.getElementById('upload-placeholder').classList.add('hidden');
                document.getElementById('image-preview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        },
    };
}

document.addEventListener('DOMContentLoaded', function () {
    const dropZone = document.getElementById('drop-zone');
    if (!dropZone) return;
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
            document.getElementById('image-input').dispatchEvent(new Event('change'));
        }
    });
});

function clearImage(e) {
    e.stopPropagation();
    const alpine = document.querySelector('[x-data="categoryEditPreview()"]')?.__x;
    if (alpine) {
        alpine.$data.form.imagePreview = null;
        alpine.$data.form.imageName = '';
    }
    document.getElementById('image-input').value = '';
}
</script>
@endpush

