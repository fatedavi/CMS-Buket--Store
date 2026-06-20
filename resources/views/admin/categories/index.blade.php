@extends('layouts.admin')

@section('content')
<div x-data="{
    search: '',
    modalOpen: {{ $errors->any() ? 'true' : 'false' }},
    isEditing: false,
    editingItem: null,
    dropZoneActive: false,
    items: {{ $categories->map(fn($c) => [
        'id' => $c->id,
        'name' => $c->name,
        'slug' => $c->slug,
        'image_url' => $c->image_url,
        'is_active' => $c->is_active,
    ])->toJson() }},
    form: {
        name: '{{ old('name') }}',
        slug: '{{ old('slug') }}',
        is_active: {{ old('is_active', true) ? 'true' : 'false' }},
        imagePreview: null,
        imageName: '',
        currentImageUrl: null,
    },
    editUrl: '{{ old('_edit_id') ? url('admin/kategori/' . old('_edit_id')) : route('admin.categories.store') }}',
    submitMethod: '{{ old('_edit_id') ? 'PUT' : 'POST' }}',

    get filteredItems() {
        const s = this.search.toLowerCase();
        return this.items.filter(i => s === '' || i.name.toLowerCase().includes(s));
    },

    openCreateModal() {
        this.isEditing = false;
        this.editingItem = null;
        this.form = { name: '', slug: '', is_active: true, imagePreview: null, imageName: '', currentImageUrl: null };
        this.editUrl = '{{ route('admin.categories.store') }}';
        this.submitMethod = 'POST';
        this.modalOpen = true;
    },
    openEditModal(item) {
        this.isEditing = true;
        this.editingItem = item;
        this.form = { name: item.name, slug: item.slug, is_active: item.is_active, imagePreview: null, imageName: '', currentImageUrl: item.image_url };
        this.editUrl = '/admin/kategori/' + item.id;
        this.submitMethod = 'PUT';
        this.modalOpen = true;
    },
    closeModal() {
        this.modalOpen = false;
        this.form = { name: '', slug: '', is_active: true, imagePreview: null, imageName: '', currentImageUrl: null };
        this.editingItem = null;
        this.dropZoneActive = false;
    },
    handleImageUpload(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (ev) => {
            this.form.imagePreview = ev.target.result;
            this.form.imageName = file.name;
        };
        reader.readAsDataURL(file);
    },
    clearImage() {
        this.form.imagePreview = null;
        this.form.imageName = '';
        if (this.$refs.imageInput) this.$refs.imageInput.value = '';
    },
    handleDrop(e) {
        this.dropZoneActive = false;
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            this.$refs.imageInput.files = e.dataTransfer.files;
            this.$refs.imageInput.dispatchEvent(new Event('change'));
        }
    },
}">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="font-playfair text-2xl text-dark-oak">Kategori</h1>
            <p class="text-sm text-warm-gray mt-0.5">Kelola kategori produk {{ setting('store_name', 'Toko Bunga') }}</p>
        </div>
        <button @click="openCreateModal()" class="bg-sage-green text-white rounded-xl px-4 py-2.5 text-sm font-medium hover:brightness-110 transition-all inline-flex items-center gap-1.5 shadow-sm shadow-sage-green/20 w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kategori
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-amber-100 p-4 mb-6 shadow-sm">
        <div class="relative">
            <svg class="w-4 h-4 text-warm-gray absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" x-model="search" placeholder="Cari kategori..." class="w-full border border-amber-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-sage-green focus:ring-2 focus:ring-sage-green/20 transition-all">
        </div>
    </div>

    {{-- Mobile Cards --}}
    <div class="block md:hidden space-y-3 mb-6">
        @forelse($categories as $category)
        <div x-show="filteredItems.some(i => i.name === '{{ $category->name }}')"
             class="bg-white rounded-xl border border-amber-100 p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="w-14 h-14 rounded-lg bg-cream overflow-hidden flex-shrink-0 border border-amber-100">
                    @if($category->image)
                    <img src="{{ $category->image_url }}" class="w-full h-full object-contain p-1">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-medium text-dark-oak truncate">{{ $category->name }}</h3>
                    <p class="text-xs text-warm-gray mt-0.5"><code class="text-[10px] bg-cream px-1.5 py-0.5 rounded">{{ $category->slug }}</code></p>
                    <div class="mt-1.5">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium {{ $category->is_active ? 'bg-sage-green/10 text-sage-green' : 'bg-gray-100 text-gray-500' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $category->is_active ? 'bg-sage-green' : 'bg-gray-400' }}"></span>
                            {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-1 flex-shrink-0">
                    <button @click="openEditModal({id: {{ $category->id }}, name: '{{ addslashes($category->name) }}', slug: '{{ $category->slug }}', image_url: '{{ $category->image_url }}', is_active: {{ $category->is_active ? 'true' : 'false' }}})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-warm-gray hover:text-sage-green hover:bg-sage-green/10 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button type="button" @click.prevent="openConfirm('delete', 'Hapus Kategori', 'Hapus kategori ini? Semua produk dalam kategori ini akan menjadi uncategorized.', '', 'category-{{ $category->id }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-warm-gray hover:text-terracotta hover:bg-terracotta/10 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-amber-100 p-6 text-center">
            <div class="w-12 h-12 bg-cream rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <p class="text-sm text-warm-gray">Belum ada kategori.</p>
            <button @click="openCreateModal()" class="text-sm text-sage-green font-medium hover:underline mt-1 inline-block">Tambah kategori</button>
        </div>
        @endforelse
        <div x-show="items.length > 0 && filteredItems.length === 0"
             class="bg-white rounded-xl border border-amber-100 p-6 text-center">
            <div class="w-12 h-12 bg-cream rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <p class="text-sm text-warm-gray">Tidak ada kategori yang cocok.</p>
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white rounded-2xl border border-amber-100 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-cream/50">
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Gambar</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Nama</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Slug</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr class="border-t border-amber-100/60 hover:bg-cream/30 transition-colors"
                        x-show="filteredItems.some(i => i.name === '{{ $category->name }}')">
                        <td class="px-5 py-3.5">
                            @if($category->image)
                            <div class="w-12 h-12 rounded-lg bg-cream overflow-hidden border border-amber-100"><img src="{{ $category->image_url }}" class="w-full h-full object-contain p-1"></div>
                            @else
                            <div class="w-12 h-12 rounded-lg bg-cream border border-amber-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-sm text-dark-oak font-medium">{{ $category->name }}</td>
                        <td class="px-5 py-3.5 text-sm text-warm-gray"><code class="text-[11px] bg-cream px-1.5 py-0.5 rounded">{{ $category->slug }}</code></td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-medium {{ $category->is_active ? 'bg-sage-green/10 text-sage-green' : 'bg-gray-100 text-gray-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $category->is_active ? 'bg-sage-green' : 'bg-gray-400' }}"></span>
                                {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1">
                                <button @click="openEditModal({id: {{ $category->id }}, name: '{{ addslashes($category->name) }}', slug: '{{ $category->slug }}', image_url: '{{ $category->image_url }}', is_active: {{ $category->is_active ? 'true' : 'false' }}})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-warm-gray hover:text-sage-green hover:bg-sage-green/10 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" id="delete-form-category-{{ $category->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click.prevent="openConfirm('delete', 'Hapus Kategori', 'Hapus kategori ini? Semua produk dalam kategori ini akan menjadi uncategorized.', '', 'category-{{ $category->id }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-warm-gray hover:text-terracotta hover:bg-terracotta/10 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="border-t border-amber-100/60">
                        <td colspan="5" class="px-5 py-12 text-center">
                            <div class="w-14 h-14 bg-cream rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <p class="text-sm text-warm-gray">Belum ada kategori.</p>
                            <button @click="openCreateModal()" class="text-sm text-sage-green font-medium hover:underline mt-1 inline-block">Tambah kategori</button>
                        </td>
                    </tr>
                    @endforelse
                    <tr x-show="items.length > 0 && filteredItems.length === 0"
                        class="border-t border-amber-100/60">
                        <td colspan="5" class="px-5 py-12 text-center">
                            <div class="w-14 h-14 bg-cream rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <p class="text-sm text-warm-gray">Tidak ada kategori yang cocok.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- CREATE / EDIT MODAL --}}
    <div x-show="modalOpen" class="fixed inset-0 z-50" x-cloak>
        <div @click="closeModal()" class="fixed inset-0 bg-dark-oak/50 backdrop-blur-sm"></div>
        <div class="fixed inset-0 flex items-start justify-center overflow-y-auto py-6 px-4">
            <div @click.stop class="bg-white rounded-2xl shadow-xl w-full max-w-3xl mx-auto my-auto"
                 x-show="modalOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                <div class="flex items-center justify-between p-6 border-b border-amber-100">
                    <h2 class="font-playfair text-lg text-dark-oak" x-text="isEditing ? 'Edit Kategori' : 'Tambah Kategori'"></h2>
                    <button @click="closeModal()" class="w-8 h-8 rounded-lg text-warm-gray hover:text-dark-oak hover:bg-cream/50 transition-all flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-6 max-h-[75vh] overflow-y-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                        <div class="lg:col-span-3">
                            <form :action="editUrl" method="POST" enctype="multipart/form-data" id="form-category-modal" class="space-y-5">
                                @csrf
                                <input type="hidden" name="_method" :value="submitMethod">
                                <input type="hidden" name="_edit_id" :value="isEditing ? editingItem.id : ''">

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-1">Nama Kategori</label>
                                    <input type="text" name="name" x-model="form.name" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                                    @error('name')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-1">Slug</label>
                                    <input type="text" name="slug" x-model="form.slug" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                                    <p class="text-xs text-warm-gray mt-1">Kosongkan untuk membuat otomatis dari nama kategori</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-2">Gambar Kategori</label>
                                    <div @dragover.prevent="dropZoneActive = true"
                                         @dragleave.prevent="dropZoneActive = false"
                                         @drop.prevent="handleDrop($event)"
                                         :class="dropZoneActive ? 'border-sage-green bg-sage-green/5' : ''"
                                         class="relative border-2 border-dashed border-sand rounded-2xl p-6 text-center cursor-pointer hover:border-sage-green hover:bg-sage-green/5 transition-all group"
                                         @click="$refs.imageInput.click()">
                                        <div x-show="!form.imagePreview && !(isEditing && form.currentImageUrl)">
                                            <div class="w-12 h-12 rounded-full bg-cream flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                                <svg class="w-6 h-6 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                            <p class="text-sm font-medium text-dark-oak">Klik atau drag & drop gambar di sini</p>
                                            <p class="text-xs text-warm-gray mt-1">PNG, JPG, WEBP — Otomatis dikompres</p>
                                        </div>
                                        <div x-show="form.imagePreview || (isEditing && form.currentImageUrl)">
                                            <img :src="form.imagePreview || form.currentImageUrl" alt="Preview" class="mx-auto max-h-48 rounded-xl object-contain shadow">
                                            <p class="text-xs text-warm-gray mt-2 truncate" x-text="form.imageName || 'Gambar saat ini'"></p>
                                            <button type="button" @click.stop="clearImage()" class="mt-2 text-xs text-terracotta hover:underline">Hapus gambar</button>
                                        </div>
                                        <input type="file" x-ref="imageInput" name="image" accept="image/*" class="hidden" @change="handleImageUpload">
                                    </div>
                                    @if($category->image)
                                    <label class="flex items-center gap-2 mt-2" x-show="isEditing && form.currentImageUrl">
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
                                    <button type="button" @click.prevent="openConfirm('save', 'Simpan Kategori', isEditing ? 'Simpan perubahan kategori?' : 'Simpan kategori baru?', '', 'form-category-modal')" class="bg-sage-green text-white rounded-xl px-6 py-2.5 font-medium hover:brightness-110 transition-all" x-text="isEditing ? 'Simpan Perubahan' : 'Simpan'"></button>
                                    <button type="button" @click="closeModal()" class="border border-sand text-dark-oak rounded-xl px-6 py-2.5 font-medium hover:bg-cream/50 transition-all">Batal</button>
                                </div>
                            </form>
                        </div>

                        {{-- Preview Panel --}}
                        <div class="lg:col-span-2">
                            <div class="bg-white rounded-xl border border-amber-100 p-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="font-playfair text-base text-dark-oak">Preview</h3>
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
        </div>
    </div>
</div>
@endsection
