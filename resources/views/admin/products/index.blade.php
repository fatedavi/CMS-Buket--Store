@extends('layouts.admin')

@section('content')
<div x-data="{
    search: '',
    filterStatus: 'Semua',
    filterCategory: 'Semua',
    modalOpen: {{ $errors->any() ? 'true' : 'false' }},
    isEditing: false,
    editingItem: null,
    dropZoneActive: false,
    items: {{ $products->map(fn($p) => [
        'id' => $p->id,
        'name' => $p->name,
        'slug' => $p->slug,
        'category_id' => $p->category_id,
        'category_name' => $p->category->name ?? '',
        'price' => $p->price,
        'description' => $p->description,
        'badge' => $p->badge,
        'status' => $p->status,
        'image_url' => $p->image_url,
    ])->toJson() }},
    form: {
        name: '{{ old('name') }}',
        slug: '{{ old('slug') }}',
        category_id: '{{ old('category_id') }}',
        categoryName: '',
        price: '{{ old('price') }}',
        description: '{{ old('description') }}',
        badge: '{{ old('badge') }}',
        status: '{{ old('status', 'Aktif') }}',
        imagePreview: null,
        imageName: '',
        currentImageUrl: null,
    },
    editUrl: '{{ old('_edit_id') ? url('admin/produk/' . old('_edit_id')) : route('admin.products.store') }}',
    submitMethod: '{{ old('_edit_id') ? 'PUT' : 'POST' }}',

    get filteredItems() {
        const s = this.search.toLowerCase();
        return this.items.filter(i =>
            (s === '' || i.name.toLowerCase().includes(s) || (i.category_name && i.category_name.toLowerCase().includes(s)))
            && (this.filterStatus === 'Semua' || i.status === this.filterStatus)
            && (this.filterCategory === 'Semua' || i.category_name === this.filterCategory)
        );
    },

    openCreateModal() {
        this.isEditing = false;
        this.editingItem = null;
        this.form = { name: '', slug: '', category_id: '', categoryName: '', price: '', description: '', badge: '', status: 'Aktif', imagePreview: null, imageName: '', currentImageUrl: null };
        this.editUrl = '{{ route('admin.products.store') }}';
        this.submitMethod = 'POST';
        this.modalOpen = true;
        this.$nextTick(() => this.updateCategoryName());
    },
    openEditModal(item) {
        this.isEditing = true;
        this.editingItem = item;
        this.form = { name: item.name, slug: item.slug, category_id: String(item.category_id), categoryName: item.category_name, price: String(item.price), description: item.description, badge: item.badge, status: item.status, imagePreview: null, imageName: '', currentImageUrl: item.image_url };
        this.editUrl = '/admin/produk/' + item.id;
        this.submitMethod = 'PUT';
        this.modalOpen = true;
    },
    closeModal() {
        this.modalOpen = false;
        this.form = { name: '', slug: '', category_id: '', categoryName: '', price: '', description: '', badge: '', status: 'Aktif', imagePreview: null, imageName: '', currentImageUrl: null };
        this.editingItem = null;
        this.dropZoneActive = false;
    },
    updateCategoryName() {
        const sel = this.$refs.categorySelect;
        if (sel) {
            const opt = sel.options[sel.selectedIndex];
            this.form.categoryName = opt && opt.value ? (opt.dataset.name || opt.text) : '';
        }
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
    formatPrice(val) {
        if (!val) return '0';
        return parseInt(val).toLocaleString('id-ID');
    },
}">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="font-playfair text-2xl text-dark-oak">Produk</h1>
            <p class="text-sm text-warm-gray mt-0.5">Kelola produk {{ setting('store_name', 'Toko Bunga') }}</p>
        </div>
        <button @click="openCreateModal()" class="bg-sage-green text-white rounded-xl px-4 py-2.5 text-sm font-medium hover:brightness-110 transition-all inline-flex items-center gap-1.5 shadow-sm shadow-sage-green/20 w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Produk
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-amber-100 p-4 mb-6 flex flex-wrap gap-3 shadow-sm">
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <svg class="w-4 h-4 text-warm-gray absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" x-model="search" placeholder="Cari produk..." class="w-full border border-amber-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-sage-green focus:ring-2 focus:ring-sage-green/20 transition-all">
            </div>
        </div>
        <select x-model="filterStatus" class="border border-amber-200 rounded-xl px-4 py-2.5 text-sm text-warm-gray focus:outline-none focus:border-sage-green focus:ring-2 focus:ring-sage-green/20 bg-white">
            <option value="Semua">Semua Status</option>
            <option value="Aktif">Aktif</option>
            <option value="Draft">Draft</option>
        </select>
        <select x-model="filterCategory" class="border border-amber-200 rounded-xl px-4 py-2.5 text-sm text-warm-gray focus:outline-none focus:border-sage-green focus:ring-2 focus:ring-sage-green/20 bg-white">
            <option value="Semua">Semua Kategori</option>
            @foreach(\App\Models\Category::all() as $cat)
            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Mobile Cards --}}
    <div class="block md:hidden space-y-3 mb-6">
        @forelse($products as $product)
        <div x-show="filteredItems.some(i => i.name === '{{ $product->name }}' && i.category_name === '{{ $product->category->name ?? '' }}' && i.status === '{{ $product->status }}')"
             class="bg-white rounded-xl border border-amber-100 p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="w-14 h-14 rounded-lg bg-cream overflow-hidden flex-shrink-0 border border-amber-100">
                    @php
                        $imgSrc = $product->image
                            ? (str_starts_with($product->image, 'http') ? $product->image : Storage::url($product->image))
                            : null;
                    @endphp
                    @if($imgSrc)
                    <img src="{{ $imgSrc }}" class="w-full h-full object-contain p-1">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-medium text-dark-oak truncate">{{ $product->name }}</h3>
                    <p class="text-xs text-warm-gray mt-0.5">{{ $product->category->name ?? '—' }}</p>
                    <div class="flex items-center gap-2 mt-1.5">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium {{ $product->status === 'Aktif' ? 'bg-sage-green/10 text-sage-green' : 'bg-amber-100 text-amber-700' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $product->status === 'Aktif' ? 'bg-sage-green' : 'bg-amber-500' }}"></span>
                            {{ $product->status }}
                        </span>
                        <span class="text-[11px] text-warm-gray">{{ $product->created_at->format('j F Y') }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-1 flex-shrink-0">
                    <button @click="openEditModal({id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', slug: '{{ $product->slug }}', category_id: {{ $product->category_id ?? 'null' }}, category_name: '{{ addslashes($product->category->name ?? '') }}', price: {{ $product->price ?? 'null' }}, description: '{{ addslashes($product->description) }}', badge: '{{ $product->badge }}', status: '{{ $product->status }}', image_url: '{{ $product->image_url }}'})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-warm-gray hover:text-sage-green hover:bg-sage-green/10 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button type="button" @click.prevent="openConfirm('delete', 'Hapus Produk', 'Hapus produk ini?', '', 'product-{{ $product->id }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-warm-gray hover:text-terracotta hover:bg-terracotta/10 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-amber-100 p-6 text-center">
            <div class="w-12 h-12 bg-cream rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4l-8 4"/></svg>
            </div>
            <p class="text-sm text-warm-gray">Belum ada produk.</p>
            <button @click="openCreateModal()" class="text-sm text-sage-green font-medium hover:underline mt-1 inline-block">Tambah produk</button>
        </div>
        @endforelse
        <div x-show="items.length > 0 && filteredItems.length === 0"
             class="bg-white rounded-xl border border-amber-100 p-6 text-center">
            <div class="w-12 h-12 bg-cream rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <p class="text-sm text-warm-gray">Tidak ada produk yang cocok dengan filter.</p>
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
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Kategori</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="border-t border-amber-100/60 hover:bg-cream/30 transition-colors"
                        x-show="filteredItems.some(i => i.name === '{{ $product->name }}' && i.category_name === '{{ $product->category->name ?? '' }}' && i.status === '{{ $product->status }}')">
                        <td class="px-5 py-3.5">
                            @php
                                $imgSrc = $product->image
                                    ? (str_starts_with($product->image, 'http') ? $product->image : Storage::url($product->image))
                                    : null;
                            @endphp
                            @if($imgSrc)
                            <div class="w-12 h-12 rounded-lg bg-cream overflow-hidden"><img src="{{ $imgSrc }}" class="w-full h-full object-contain p-1"></div>
                            @else
                            <div class="w-12 h-12 rounded-lg bg-cream flex items-center justify-center">
                                <svg class="w-5 h-5 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-sm text-dark-oak font-medium">{{ $product->name }}</td>
                        <td class="px-5 py-3.5 text-sm text-warm-gray">{{ $product->category->name ?? '—' }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-medium {{ $product->status === 'Aktif' ? 'bg-sage-green/10 text-sage-green' : 'bg-amber-100 text-amber-700' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $product->status === 'Aktif' ? 'bg-sage-green' : 'bg-amber-500' }}"></span>
                                {{ $product->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-warm-gray">{{ $product->created_at->format('j F Y') }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1">
                                <button @click="openEditModal({id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', slug: '{{ $product->slug }}', category_id: {{ $product->category_id ?? 'null' }}, category_name: '{{ addslashes($product->category->name ?? '') }}', price: {{ $product->price ?? 'null' }}, description: '{{ addslashes($product->description) }}', badge: '{{ $product->badge }}', status: '{{ $product->status }}', image_url: '{{ $product->image_url }}'})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-warm-gray hover:text-sage-green hover:bg-sage-green/10 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" id="delete-form-product-{{ $product->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click.prevent="openConfirm('delete', 'Hapus Produk', 'Hapus produk ini?', '', 'product-{{ $product->id }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-warm-gray hover:text-terracotta hover:bg-terracotta/10 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="border-t border-amber-100/60">
                        <td colspan="6" class="px-5 py-12 text-center">
                            <div class="w-14 h-14 bg-cream rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4l-8 4"/></svg>
                            </div>
                            <p class="text-sm text-warm-gray">Belum ada produk.</p>
                            <button @click="openCreateModal()" class="text-sm text-sage-green font-medium hover:underline mt-1 inline-block">Tambah produk</button>
                        </td>
                    </tr>
                    @endforelse
                    <tr x-show="items.length > 0 && filteredItems.length === 0"
                        class="border-t border-amber-100/60">
                        <td colspan="6" class="px-5 py-12 text-center">
                            <div class="w-14 h-14 bg-cream rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <p class="text-sm text-warm-gray">Tidak ada produk yang cocok dengan filter.</p>
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
            <div @click.stop class="bg-white rounded-2xl shadow-xl w-full max-w-5xl mx-auto my-auto"
                 x-show="modalOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                <div class="flex items-center justify-between p-6 border-b border-amber-100">
                    <h2 class="font-playfair text-lg text-dark-oak" x-text="isEditing ? 'Edit Produk' : 'Tambah Produk'"></h2>
                    <button @click="closeModal()" class="w-8 h-8 rounded-lg text-warm-gray hover:text-dark-oak hover:bg-cream/50 transition-all flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-6 max-h-[75vh] overflow-y-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                        <div class="lg:col-span-3">
                            <form :action="editUrl" method="POST" enctype="multipart/form-data" id="form-product-modal" class="space-y-5">
                                @csrf
                                <input type="hidden" name="_method" :value="submitMethod">
                                <input type="hidden" name="_edit_id" :value="isEditing ? editingItem.id : ''">

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-1">Nama Produk</label>
                                    <input type="text" name="name" x-model="form.name" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                                    @error('name')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-1">Slug</label>
                                    <input type="text" name="slug" x-model="form.slug" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                                    <p class="text-xs text-warm-gray mt-1">Kosongkan untuk membuat otomatis dari nama produk</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-1">Kategori</label>
                                    <select name="category_id" x-model="form.category_id" x-ref="categorySelect" @change="updateCategoryName()" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                                        <option value="">Pilih kategori</option>
                                        @foreach(\App\Models\Category::where('is_active', true)->get() as $cat)
                                        <option value="{{ $cat->id }}" data-name="{{ $cat->name }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-1">Harga</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-warm-gray text-sm">Rp</span>
                                        <input type="number" name="price" x-model="form.price" min="0" step="500" class="w-full border border-sand rounded-xl pl-10 pr-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                                    </div>
                                    @error('price')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-1">Deskripsi</label>
                                    <textarea name="description" rows="4" x-model="form.description" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-2">Gambar Produk</label>

                                    <div x-show="isEditing && form.currentImageUrl && !form.imagePreview" class="mb-3 flex items-center gap-4 p-3 bg-cream rounded-xl border border-amber-100">
                                        <img :src="form.currentImageUrl" alt="Gambar saat ini" class="w-20 h-20 object-contain p-1 bg-cream rounded-xl shadow-sm flex-shrink-0">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-medium text-dark-oak">Gambar saat ini</p>
                                            <label class="flex items-center gap-1.5 mt-2 cursor-pointer">
                                                <input type="checkbox" name="remove_image" value="1" class="rounded border-sand text-terracotta">
                                                <span class="text-xs text-terracotta">Hapus gambar ini</span>
                                            </label>
                                        </div>
                                    </div>

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
                                            <p class="text-sm font-medium text-dark-oak" x-text="isEditing ? 'Pilih gambar baru untuk mengganti' : 'Klik atau drag & drop gambar di sini'"></p>
                                            <p class="text-xs text-warm-gray mt-1">PNG, JPG, WEBP — Otomatis dikompres</p>
                                        </div>
                                        <div x-show="form.imagePreview || (isEditing && form.currentImageUrl)">
                                            <img :src="form.imagePreview || form.currentImageUrl" alt="Preview" class="mx-auto max-h-48 rounded-xl object-contain shadow">
                                            <p class="text-xs text-warm-gray mt-2 truncate" x-text="form.imageName || 'Gambar saat ini'"></p>
                                            <button type="button" @click.stop="clearImage()" class="mt-2 text-xs text-terracotta hover:underline">Hapus gambar</button>
                                        </div>
                                        <input type="file" x-ref="imageInput" name="image" accept="image/*" class="hidden" @change="handleImageUpload">
                                    </div>
                                    @error('image')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-1">Badge</label>
                                    <select name="badge" x-model="form.badge" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                                        <option value="">Tidak ada</option>
                                        @foreach(['Bestseller','Baru','Populer'] as $b)
                                        <option value="{{ $b }}">{{ $b }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-1">Status</label>
                                    <select name="status" x-model="form.status" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                                        <option value="Aktif">Aktif</option>
                                        <option value="Draft">Draft</option>
                                    </select>
                                </div>

                                <div class="flex gap-3 pt-2">
                                    <button type="button" @click.prevent="openConfirm('save', 'Simpan Produk', isEditing ? 'Simpan perubahan produk?' : 'Simpan produk baru?', '', 'form-product-modal')" class="bg-sage-green text-white rounded-xl px-6 py-2.5 font-medium hover:brightness-110 transition-all" x-text="isEditing ? 'Simpan Perubahan' : 'Simpan'"></button>
                                    <button type="button" @click="closeModal()" class="border border-sand text-dark-oak rounded-xl px-6 py-2.5 font-medium hover:bg-cream/50 transition-all">Batal</button>
                                </div>
                            </form>
                        </div>

                        {{-- Preview Panel --}}
                        <div class="lg:col-span-2">
                            <div class="sticky top-0">
                                <div class="bg-white rounded-2xl border border-amber-100 p-4">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="font-playfair text-base text-dark-oak">Preview</h3>
                                        <span class="text-[10px] px-2 py-0.5 rounded-full" :class="form.status === 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'" x-text="form.status"></span>
                                    </div>

                                    <div class="bg-white border border-amber-100 rounded-xl overflow-hidden group transition-all duration-500 max-w-sm mx-auto">
                                        <div class="relative aspect-[4/3] overflow-hidden bg-cream">
                                            <template x-if="form.imagePreview || form.currentImageUrl">
                                                <img :src="form.imagePreview || form.currentImageUrl" alt="" class="w-full h-full object-contain p-2">
                                            </template>
                                            <template x-if="!(form.imagePreview || form.currentImageUrl)">
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
            </div>
        </div>
    </div>
</div>
@endsection
