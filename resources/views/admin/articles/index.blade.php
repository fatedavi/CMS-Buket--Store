@extends('layouts.admin')

@section('content')
<div x-data="{
    search: '',
    filterCategory: 'Semua',
    modalOpen: {{ $errors->any() ? 'true' : 'false' }},
    isEditing: false,
    editingItem: null,
    dropZoneActive: false,
    items: {{ $articles->map(fn($a) => [
        'id' => $a->id,
        'title' => $a->title,
        'slug' => $a->slug,
        'category' => $a->category,
        'excerpt' => $a->excerpt,
        'content' => $a->content,
        'image_url' => $a->image_url,
        'date' => $a->date,
    ])->toJson() }},
    form: {
        title: '{{ old('title') }}',
        slug: '{{ old('slug') }}',
        category: '{{ old('category', 'Tips & Trik') }}',
        excerpt: '{{ old('excerpt') }}',
        date: '{{ old('date', now()->format('j F Y')) }}',
        imagePreview: null,
        imageName: '',
        currentImageUrl: null,
    },
    editUrl: '{{ old('_edit_id') ? url('admin/artikel/' . old('_edit_id')) : route('admin.articles.store') }}',
    submitMethod: '{{ old('_edit_id') ? 'PUT' : 'POST' }}',

    get filteredItems() {
        const s = this.search.toLowerCase();
        return this.items.filter(i =>
            (s === '' || i.title.toLowerCase().includes(s) || i.category.toLowerCase().includes(s))
            && (this.filterCategory === 'Semua' || i.category === this.filterCategory)
        );
    },

    openCreateModal() {
        this.isEditing = false;
        this.editingItem = null;
        this.form = { title: '', slug: '', category: 'Tips & Trik', excerpt: '', date: '{{ now()->format('j F Y') }}', imagePreview: null, imageName: '', currentImageUrl: null };
        this.editUrl = '{{ route('admin.articles.store') }}';
        this.submitMethod = 'POST';
        this.modalOpen = true;
    },
    openEditModal(item) {
        this.isEditing = true;
        this.editingItem = item;
        this.form = { title: item.title, slug: item.slug, category: item.category, excerpt: item.excerpt, date: item.date, imagePreview: null, imageName: '', currentImageUrl: item.image_url };
        this.editUrl = '/admin/artikel/' + item.id;
        this.submitMethod = 'PUT';
        this.modalOpen = true;
    },
    closeModal() {
        this.modalOpen = false;
        this.form = { title: '', slug: '', category: 'Tips & Trik', excerpt: '', date: '{{ now()->format('j F Y') }}', imagePreview: null, imageName: '', currentImageUrl: null };
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
            <h1 class="font-playfair text-2xl text-dark-oak">Artikel</h1>
            <p class="text-sm text-warm-gray mt-0.5">Kelola artikel blog {{ setting('store_name', 'Toko Bunga') }}</p>
        </div>
        <button @click="openCreateModal()" class="bg-sage-green text-white rounded-xl px-4 py-2.5 text-sm font-medium hover:brightness-110 transition-all inline-flex items-center gap-1.5 shadow-sm shadow-sage-green/20 w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Artikel
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-amber-100 p-4 mb-6 flex flex-wrap gap-3 shadow-sm">
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <svg class="w-4 h-4 text-warm-gray absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" x-model="search" placeholder="Cari artikel..." class="w-full border border-amber-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-sage-green focus:ring-2 focus:ring-sage-green/20 transition-all">
            </div>
        </div>
        <select x-model="filterCategory" class="border border-amber-200 rounded-xl px-4 py-2.5 text-sm text-warm-gray focus:outline-none focus:border-sage-green focus:ring-2 focus:ring-sage-green/20 bg-white">
            <option value="Semua">Semua Kategori</option>
            @php $cats = $articles->pluck('category')->unique()->sort(); @endphp
            @foreach($cats as $cat)
            <option value="{{ $cat }}">{{ $cat }}</option>
            @endforeach
        </select>
    </div>

    {{-- Mobile Cards --}}
    <div class="block md:hidden space-y-3 mb-6">
        @forelse($articles as $article)
        <div x-show="filteredItems.some(i => i.title === '{{ $article->title }}' && i.category === '{{ $article->category }}')"
             class="bg-white rounded-xl border border-amber-100 p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="w-14 h-14 rounded-lg bg-cream overflow-hidden flex-shrink-0 border border-amber-100">
                    <img src="{{ $article->image_url }}" class="w-full h-full object-contain p-1">
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-medium text-dark-oak truncate">{{ $article->title }}</h3>
                    <div class="flex items-center gap-2 mt-1.5">
                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-medium bg-cream text-warm-gray">{{ $article->category }}</span>
                        <span class="text-[11px] text-warm-gray">{{ $article->date }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-1 flex-shrink-0">
                    <button @click="openEditModal({id: {{ $article->id }}, title: '{{ addslashes($article->title) }}', slug: '{{ $article->slug }}', category: '{{ $article->category }}', excerpt: '{{ addslashes($article->excerpt) }}', content: '{{ addslashes($article->content) }}', image_url: '{{ $article->image_url }}', date: '{{ $article->date }}'})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-warm-gray hover:text-sage-green hover:bg-sage-green/10 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button type="button" @click.prevent="openConfirm('delete', 'Hapus Artikel', 'Hapus artikel ini?', '', 'article-{{ $article->id }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-warm-gray hover:text-terracotta hover:bg-terracotta/10 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-amber-100 p-6 text-center">
            <div class="w-12 h-12 bg-cream rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <p class="text-sm text-warm-gray">Belum ada artikel.</p>
            <button @click="openCreateModal()" class="text-sm text-sage-green font-medium hover:underline mt-1 inline-block">Tambah artikel</button>
        </div>
        @endforelse
        <div x-show="items.length > 0 && filteredItems.length === 0"
             class="bg-white rounded-xl border border-amber-100 p-6 text-center">
            <div class="w-12 h-12 bg-cream rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <p class="text-sm text-warm-gray">Tidak ada artikel yang cocok dengan filter.</p>
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white rounded-2xl border border-amber-100 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-cream/50">
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Gambar</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Judul</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Kategori</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                    <tr class="border-t border-amber-100/60 hover:bg-cream/30 transition-colors"
                        x-show="filteredItems.some(i => i.title === '{{ $article->title }}' && i.category === '{{ $article->category }}')">
                        <td class="px-5 py-3.5">
                            <div class="w-12 h-12 rounded-lg bg-cream flex items-center justify-center overflow-hidden border border-amber-100">
                                <img src="{{ $article->image_url }}" class="w-full h-full object-contain p-1">
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-dark-oak font-medium max-w-xs truncate">{{ $article->title }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-medium bg-cream text-warm-gray">{{ $article->category }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-warm-gray">{{ $article->date }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1">
                                <button @click="openEditModal({id: {{ $article->id }}, title: '{{ addslashes($article->title) }}', slug: '{{ $article->slug }}', category: '{{ $article->category }}', excerpt: '{{ addslashes($article->excerpt) }}', content: '{{ addslashes($article->content) }}', image_url: '{{ $article->image_url }}', date: '{{ $article->date }}'})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-warm-gray hover:text-sage-green hover:bg-sage-green/10 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="inline" id="delete-form-article-{{ $article->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click.prevent="openConfirm('delete', 'Hapus Artikel', 'Hapus artikel ini?', '', 'article-{{ $article->id }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-warm-gray hover:text-terracotta hover:bg-terracotta/10 transition-all">
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
                                <svg class="w-6 h-6 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <p class="text-sm text-warm-gray">Belum ada artikel.</p>
                            <button @click="openCreateModal()" class="text-sm text-sage-green font-medium hover:underline mt-1 inline-block">Tambah artikel</button>
                        </td>
                    </tr>
                    @endforelse
                    <tr x-show="items.length > 0 && filteredItems.length === 0"
                        class="border-t border-amber-100/60">
                        <td colspan="5" class="px-5 py-12 text-center">
                            <div class="w-14 h-14 bg-cream rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <p class="text-sm text-warm-gray">Tidak ada artikel yang cocok dengan filter.</p>
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
            <div @click.stop class="bg-white rounded-2xl shadow-xl w-full max-w-4xl mx-auto my-auto"
                 x-show="modalOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                <div class="flex items-center justify-between p-6 border-b border-amber-100">
                    <h2 class="font-playfair text-lg text-dark-oak" x-text="isEditing ? 'Edit Artikel' : 'Tambah Artikel'"></h2>
                    <button @click="closeModal()" class="w-8 h-8 rounded-lg text-warm-gray hover:text-dark-oak hover:bg-cream/50 transition-all flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-6 max-h-[75vh] overflow-y-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                        <div class="lg:col-span-3">
                            <form :action="editUrl" method="POST" enctype="multipart/form-data" id="form-article-modal" class="space-y-4">
                                @csrf
                                <input type="hidden" name="_method" :value="submitMethod">
                                <input type="hidden" name="_edit_id" :value="isEditing ? editingItem.id : ''">

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-1">Judul Artikel</label>
                                    <input type="text" name="title" x-model="form.title" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                                    @error('title')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-1">Slug</label>
                                    <input type="text" name="slug" x-model="form.slug" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                                    <p class="text-xs text-warm-gray mt-1">Kosongkan untuk membuat otomatis dari judul</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-1">Kategori</label>
                                    <select name="category" x-model="form.category" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                                        <option value="Tips & Trik">Tips & Trik</option>
                                        <option value="Panduan">Panduan</option>
                                        <option value="Inspirasi">Inspirasi</option>
                                    </select>
                                    @error('category')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-1">Excerpt (cuplikan singkat)</label>
                                    <textarea name="excerpt" rows="3" x-model="form.excerpt" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-1">Konten (HTML)</label>
                                    <textarea name="content" rows="8" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20 font-mono text-sm">{{ old('content') }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-2">Gambar Artikel</label>
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
                                    @if(isset($article) && $article->image)
                                    <label class="flex items-center gap-2 mt-2" x-show="isEditing && form.currentImageUrl">
                                        <input type="checkbox" name="remove_image" value="1" class="rounded border-sand text-terracotta focus:ring-terracotta">
                                        <span class="text-xs text-terracotta">Hapus gambar saat ini</span>
                                    </label>
                                    @endif
                                    @error('image')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-1">Tanggal</label>
                                    <input type="text" name="date" x-model="form.date" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                                    <p class="text-xs text-warm-gray mt-1">Format: 1 January 2024</p>
                                </div>

                                <div class="flex gap-3 pt-2">
                                    <button type="button" @click.prevent="openConfirm('save', 'Simpan Artikel', isEditing ? 'Simpan perubahan artikel?' : 'Simpan artikel baru?', '', 'form-article-modal')" class="bg-sage-green text-white rounded-xl px-6 py-2.5 font-medium hover:brightness-110 transition-all" x-text="isEditing ? 'Simpan Perubahan' : 'Simpan'"></button>
                                    <button type="button" @click="closeModal()" class="border border-sand text-dark-oak rounded-xl px-6 py-2.5 font-medium hover:bg-cream/50 transition-all">Batal</button>
                                </div>
                            </form>
                        </div>

                        {{-- Preview Panel --}}
                        <div class="lg:col-span-2">
                            <div class="sticky top-0">
                                <div class="bg-white rounded-2xl border border-amber-100 p-4">
                                    <h3 class="font-playfair text-base text-dark-oak mb-4">Preview</h3>

                                    <div class="bg-white border border-amber-100 rounded-xl overflow-hidden group max-w-sm mx-auto">
                                        <div class="aspect-[4/3] overflow-hidden bg-cream">
                                            <template x-if="form.imagePreview || form.currentImageUrl">
                                                <img :src="form.imagePreview || form.currentImageUrl" alt="" class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform">
                                            </template>
                                            <template x-if="!(form.imagePreview || form.currentImageUrl)">
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <svg class="w-10 h-10 text-sand/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                            </template>
                                        </div>
                                        <div class="p-4">
                                            <span class="bg-cream text-warm-gray text-xs rounded-lg px-2 py-1" x-text="form.category || 'Kategori'"></span>
                                            <h3 class="font-medium text-dark-oak mt-2 mb-1 line-clamp-2" x-text="form.title || 'Judul Artikel'"></h3>
                                            <p class="text-xs text-warm-gray" x-text="form.date || '{{ now()->format('j F Y') }}'"></p>
                                            <p class="text-sm text-warm-gray mt-2 line-clamp-2" x-text="form.excerpt || 'Cuplikan artikel akan tampil di sini...'"></p>
                                            <span class="text-sm text-sage-green mt-2 block">Baca selengkapnya →</span>
                                        </div>
                                    </div>

                                    <p class="text-[11px] text-warm-gray text-center mt-4">Tampilan pada halaman blog</p>
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
