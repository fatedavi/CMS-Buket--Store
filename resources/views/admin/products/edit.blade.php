@extends('layouts.admin')

@section('content')
@php
    $currentImg = $product->image
        ? (str_starts_with($product->image, 'http') ? $product->image : Storage::url($product->image))
        : null;
@endphp

<div x-data="productPreview()" class="mb-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="font-playfair text-2xl text-dark-oak">Edit Produk</h1>
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
        {{-- FORM --}}
        <div :class="showPreview ? 'lg:col-span-3' : ''">
            <div class="bg-white rounded-2xl border border-amber-100 p-6">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="form-product-edit-{{ $product->id }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-dark-oak mb-1">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" x-model="form.name"
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
                        <select name="category_id" x-model="form.category_id"
                                class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                            <option value="">Pilih kategori</option>
                            @foreach(\App\Models\Category::where('is_active', true)->get() as $cat)
                            <option value="{{ $cat->id }}" data-name="{{ $cat->name }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-oak mb-1">Harga</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-warm-gray text-sm">Rp</span>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" step="500" x-model="form.price"
                                   class="w-full border border-sand rounded-xl pl-10 pr-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                        </div>
                        @error('price')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-oak mb-1">Deskripsi</label>
                        <textarea name="description" rows="4" x-model="form.description"
                                  class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">{{ old('description', $product->description) }}</textarea>
                    </div>

                    {{-- Upload Gambar --}}
                    <div>
                        <label class="block text-sm font-medium text-dark-oak mb-2">Gambar Produk</label>

                        @if($currentImg)
                        <div id="current-image-wrap" class="mb-3 flex items-center gap-4 p-3 bg-cream rounded-xl border border-amber-100">
                            <img src="{{ $currentImg }}" alt="Gambar saat ini" class="w-20 h-20 object-contain p-1 bg-cream rounded-xl shadow-sm flex-shrink-0">
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
                                <p class="text-xs text-warm-gray mt-1">PNG, JPG, WEBP — Otomatis dikompres</p>
                            </div>

                            <div id="image-preview" class="hidden">
                                <img id="preview-img" src="" alt="Preview" class="mx-auto max-h-48 rounded-xl object-contain shadow">
                                <p id="preview-name" class="text-xs text-warm-gray mt-2 truncate"></p>
                                <button type="button" onclick="clearImage(event)"
                                        class="mt-2 text-xs text-terracotta hover:underline">Batal pilih gambar baru</button>
                            </div>

                            <input type="file" id="image-input" name="image" accept="image/*" class="hidden" @change="handleImageUpload">
                        </div>
                        @error('image')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-oak mb-1">Badge</label>
                        <select name="badge" x-model="form.badge" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                            <option value="">Tidak ada</option>
                            @foreach(['Bestseller','Baru','Populer'] as $b)
                            <option value="{{ $b }}" {{ old('badge', $product->badge) === $b ? 'selected' : '' }}>{{ $b }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-oak mb-1">Status</label>
                        <select name="status" x-model="form.status" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                            <option value="Aktif" {{ old('status', $product->status) === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Draft" {{ old('status', $product->status) === 'Draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click.prevent="openConfirm('save', 'Simpan Perubahan', 'Simpan perubahan produk?', '', 'form-product-edit-{{ $product->id }}')" class="bg-sage-green text-white rounded-xl px-6 py-2.5 font-medium hover:brightness-110 transition-all">Simpan Perubahan</button>
                        <a href="{{ route('admin.products') }}" class="border border-sand text-dark-oak rounded-xl px-6 py-2.5 font-medium hover:bg-cream transition-all">Batal</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- PREVIEW PANEL --}}
        <div x-show="showPreview" class="lg:col-span-2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
            <div class="sticky top-8">
                <div class="bg-white rounded-2xl border border-amber-100 p-4 mb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-playfair text-base text-dark-oak">Preview Produk</h3>
                        <span class="text-[10px] px-2 py-0.5 rounded-full" :class="form.status === 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'" x-text="form.status"></span>
                    </div>

                    <div class="bg-white border border-amber-100 rounded-xl overflow-hidden group transition-all duration-500 max-w-sm mx-auto">
                        <div class="relative aspect-[4/3] overflow-hidden bg-cream">
                            <template x-if="form.imagePreview">
                                <img :src="form.imagePreview" alt="" class="w-full h-full object-contain p-2">
                            </template>
                            <template x-if="!form.imagePreview">
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-sand/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            </template>
                            <template x-if="form.badge">
                                <span class="absolute top-3 left-3 bg-sage-green text-white text-xs rounded-lg px-2.5 py-1 font-medium" x-text="form.badge"></span>
                            </template>
                            <div x-show="form.status !== 'Aktif'" class="absolute inset-0 bg-dark-oak/40 flex items-center justify-center">
                                <span class="bg-white/90 text-dark-oak text-xs font-medium px-3 py-1.5 rounded-full">Draft</span>
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="font-playfair text-base text-dark-oak mb-1 line-clamp-1" x-text="form.name || 'Nama Produk'"></h3>
                            <p class="text-xs text-warm-gray mb-3" x-text="form.categoryName || 'Kategori'"></p>
                            <template x-if="form.price">
                                <p class="text-sm font-semibold text-sage-green mb-4" x-text="'Rp ' + formatPrice(form.price)"></p>
                            </template>
                            <template x-if="!form.price">
                                <p class="text-sm text-warm-gray mb-4">Hubungi untuk harga</p>
                            </template>
                            <div class="bg-sage-green text-white rounded-lg py-2.5 text-xs text-center font-medium">Pesan via WA</div>
                        </div>
                    </div>

                    <p class="text-[11px] text-warm-gray text-center mt-4">Tampilan pada halaman homepage & katalog</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function productPreview() {
    return {
        showPreview: false,
        form: {
            name: @js(old('name', $product->name)),
            category_id: @js(old('category_id', $product->category_id)),
            categoryName: @js($product->category?->name ?? ''),
            price: @js(old('price', $product->price)),
            description: @js(old('description', $product->description)),
            badge: @js(old('badge', $product->badge)),
            status: @js(old('status', $product->status)),
            imagePreview: @js($currentImg ?? '') || null,
            imageName: '',
        },
        init() {
            this.updateCategoryName();
            this.$watch('form.category_id', () => this.updateCategoryName());
        },
        updateCategoryName() {
            const sel = document.querySelector('select[name="category_id"]');
            if (!sel) return;
            const opt = sel.options[sel.selectedIndex];
            this.form.categoryName = opt && opt.value ? (opt.dataset.name || opt.text) : '';
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
                const cb = document.getElementById('remove-cb');
                if (cb) cb.checked = false;
            };
            reader.readAsDataURL(file);
        },
        formatPrice(val) {
            if (!val) return '0';
            return parseInt(val).toLocaleString('id-ID');
        },
    };
}

const dropZone = document.getElementById('drop-zone');
if (dropZone) {
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
}

function clearImage(e) {
    e.stopPropagation();
    const alpine = document.querySelector('[x-data="productPreview()"]')?.__x;
    if (alpine) {
        alpine.$data.form.imagePreview = null;
        alpine.$data.form.imageName = '';
    }
    document.getElementById('image-input').value = '';
    document.getElementById('upload-placeholder').classList.remove('hidden');
    document.getElementById('image-preview').classList.add('hidden');
}

function toggleRemove(cb) {
    if (cb.checked) {
        document.getElementById('image-input').value = '';
        document.getElementById('upload-placeholder').classList.remove('hidden');
        document.getElementById('image-preview').classList.add('hidden');
    }
}
</script>
@endpush
