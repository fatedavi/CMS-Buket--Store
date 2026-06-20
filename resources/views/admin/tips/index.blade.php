@extends('layouts.admin')

@section('content')
<div x-data="{
    search: '',
    filterActive: 'Semua',
    modalOpen: {{ $errors->any() ? 'true' : 'false' }},
    isEditing: false,
    editingItem: null,
    dropZoneActive: false,
    items: {{ $tips->map(fn($t) => [
        'id' => $t->id,
        'title' => $t->title,
        'is_active' => $t->is_active,
        'icon' => $t->icon ?? 'lightbulb',
        'content' => $t->content,
        'background_image_url' => $t->background_image_url,
        'order' => $t->order,
    ])->toJson() }},
    form: {
        title: '{{ old('title') }}',
        content: '{{ old('content') }}',
        is_active: {{ old('is_active', true) ? 'true' : 'false' }},
        icon: '{{ old('icon', 'lightbulb') }}',
        order: '{{ old('order', 0) }}',
        bgPreview: null,
        bgName: '',
        currentBgUrl: null,
    },
    editUrl: '{{ old('_edit_id') ? url('admin/tips/' . old('_edit_id')) : route('admin.tips.store') }}',
    submitMethod: '{{ old('_edit_id') ? 'PUT' : 'POST' }}',

    get filteredItems() {
        const s = this.search.toLowerCase();
        return this.items.filter(i =>
            (s === '' || i.title.toLowerCase().includes(s))
            && (this.filterActive === 'Semua'
                || (this.filterActive === 'Aktif' && i.is_active)
                || (this.filterActive === 'Nonaktif' && !i.is_active))
        );
    },

    openCreateModal() {
        this.isEditing = false;
        this.editingItem = null;
        this.form = { title: '', content: '', is_active: true, icon: 'lightbulb', order: '0', bgPreview: null, bgName: '', currentBgUrl: null };
        this.editUrl = '{{ route('admin.tips.store') }}';
        this.submitMethod = 'POST';
        this.modalOpen = true;
    },
    openEditModal(item) {
        this.isEditing = true;
        this.editingItem = item;
        this.form = { title: item.title, content: item.content, is_active: item.is_active, icon: item.icon, order: String(item.order), bgPreview: null, bgName: '', currentBgUrl: item.background_image_url };
        this.editUrl = '/admin/tips/' + item.id;
        this.submitMethod = 'PUT';
        this.modalOpen = true;
    },
    closeModal() {
        this.modalOpen = false;
        this.form = { title: '', content: '', is_active: true, icon: 'lightbulb', order: '0', bgPreview: null, bgName: '', currentBgUrl: null };
        this.editingItem = null;
        this.dropZoneActive = false;
    },
    handleBgUpload(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (ev) => {
            this.form.bgPreview = ev.target.result;
            this.form.bgName = file.name;
        };
        reader.readAsDataURL(file);
    },
    clearBg() {
        this.form.bgPreview = null;
        this.form.bgName = '';
        if (this.$refs.bgInput) this.$refs.bgInput.value = '';
    },
    handleBgDrop(e) {
        this.dropZoneActive = false;
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            this.$refs.bgInput.files = e.dataTransfer.files;
            this.$refs.bgInput.dispatchEvent(new Event('change'));
        }
    },
}">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="font-playfair text-2xl text-dark-oak">Tips & Funfact</h1>
            <p class="text-sm text-warm-gray mt-0.5">Kelola tips dan fakta menarik {{ setting('store_name', 'Toko Bunga') }}</p>
        </div>
        <button @click="openCreateModal()" class="bg-sage-green text-white rounded-xl px-4 py-2.5 text-sm font-medium hover:brightness-110 transition-all inline-flex items-center gap-1.5 shadow-sm shadow-sage-green/20 w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Tips
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-amber-100 p-4 mb-6 flex flex-wrap gap-3 shadow-sm">
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <svg class="w-4 h-4 text-warm-gray absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" x-model="search" placeholder="Cari tips..." class="w-full border border-amber-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-sage-green focus:ring-2 focus:ring-sage-green/20 transition-all">
            </div>
        </div>
        <select x-model="filterActive" class="border border-amber-200 rounded-xl px-4 py-2.5 text-sm text-warm-gray focus:outline-none focus:border-sage-green focus:ring-2 focus:ring-sage-green/20 bg-white">
            <option value="Semua">Semua Status</option>
            <option value="Aktif">Aktif</option>
            <option value="Nonaktif">Nonaktif</option>
        </select>
    </div>

    {{-- Mobile Cards --}}
    <div class="block md:hidden space-y-3 mb-6">
        @forelse($tips as $tip)
        <div x-show="filteredItems.some(i => i.title === '{{ $tip->title }}')"
             class="bg-white rounded-xl border border-amber-100 p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 space-y-1">
                    @if($tip->background_image_url)
                    <div class="w-16 h-10 rounded-lg bg-cream overflow-hidden border border-amber-100"><img src="{{ $tip->background_image_url }}" class="w-full h-full object-contain p-1"></div>
                    @else
                    <div class="w-16 h-10 rounded-lg bg-cream border border-amber-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    @endif
                    <div><x-icons.tip :icon="$tip->icon ?? 'lightbulb'" class="w-5 h-5 text-dark-oak" /></div>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-medium text-dark-oak truncate">{{ $tip->title }}</h3>
                    <p class="text-xs text-warm-gray mt-0.5 line-clamp-2">{{ Str::limit($tip->content, 80) }}</p>
                    <div class="flex items-center gap-2 mt-1.5">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-cream text-[10px] font-medium text-warm-gray">{{ $tip->order }}</span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium {{ $tip->is_active ? 'bg-sage-green/10 text-sage-green' : 'bg-gray-100 text-gray-500' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $tip->is_active ? 'bg-sage-green' : 'bg-gray-400' }}"></span>
                            {{ $tip->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-1 flex-shrink-0">
                    <button @click="openEditModal({id: {{ $tip->id }}, title: '{{ addslashes($tip->title) }}', content: '{{ addslashes($tip->content) }}', is_active: {{ $tip->is_active ? 'true' : 'false' }}, icon: '{{ $tip->icon ?? 'lightbulb' }}', order: {{ $tip->order }}, background_image_url: '{{ $tip->background_image_url }}'})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-warm-gray hover:text-sage-green hover:bg-sage-green/10 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button type="button" @click.prevent="openConfirm('delete', 'Hapus Tips', 'Hapus tips ini?', '', 'tip-{{ $tip->id }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-warm-gray hover:text-terracotta hover:bg-terracotta/10 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-amber-100 p-6 text-center">
            <div class="w-12 h-12 bg-cream rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            </div>
            <p class="text-sm text-warm-gray">Belum ada tips.</p>
            <button @click="openCreateModal()" class="text-sm text-sage-green font-medium hover:underline mt-1 inline-block">Tambah tips</button>
        </div>
        @endforelse
        <div x-show="items.length > 0 && filteredItems.length === 0"
             class="bg-white rounded-xl border border-amber-100 p-6 text-center">
            <div class="w-12 h-12 bg-cream rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <p class="text-sm text-warm-gray">Tidak ada tips yang cocok dengan filter.</p>
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white rounded-2xl border border-amber-100 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-cream/50">
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Gambar</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Icon</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Judul</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Konten</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Urutan</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tips as $tip)
                    <tr class="border-t border-amber-100/60 hover:bg-cream/30 transition-colors"
                        x-show="filteredItems.some(i => i.title === '{{ $tip->title }}')">
                        <td class="px-5 py-3.5">
                            @if($tip->background_image_url)
                            <div class="w-20 h-12 rounded-lg bg-cream overflow-hidden border border-amber-100"><img src="{{ $tip->background_image_url }}" class="w-full h-full object-contain p-1"></div>
                            @else
                            <div class="w-20 h-12 rounded-lg bg-cream border border-amber-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5"><x-icons.tip :icon="$tip->icon ?? 'lightbulb'" class="w-5 h-5 text-dark-oak" /></td>
                        <td class="px-5 py-3.5 text-sm text-dark-oak font-medium">{{ $tip->title }}</td>
                        <td class="px-5 py-3.5 text-sm text-warm-gray max-w-xs truncate">{{ Str::limit($tip->content, 80) }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-cream text-xs font-medium text-warm-gray">{{ $tip->order }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-medium {{ $tip->is_active ? 'bg-sage-green/10 text-sage-green' : 'bg-gray-100 text-gray-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $tip->is_active ? 'bg-sage-green' : 'bg-gray-400' }}"></span>
                                {{ $tip->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1">
                                <button @click="openEditModal({id: {{ $tip->id }}, title: '{{ addslashes($tip->title) }}', content: '{{ addslashes($tip->content) }}', is_active: {{ $tip->is_active ? 'true' : 'false' }}, icon: '{{ $tip->icon ?? 'lightbulb' }}', order: {{ $tip->order }}, background_image_url: '{{ $tip->background_image_url }}'})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-warm-gray hover:text-sage-green hover:bg-sage-green/10 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="{{ route('admin.tips.destroy', $tip) }}" method="POST" class="inline" id="delete-form-tip-{{ $tip->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click.prevent="openConfirm('delete', 'Hapus Tips', 'Hapus tips ini?', '', 'tip-{{ $tip->id }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-warm-gray hover:text-terracotta hover:bg-terracotta/10 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="border-t border-amber-100/60">
                        <td colspan="7" class="px-5 py-12 text-center">
                            <div class="w-14 h-14 bg-cream rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                            </div>
                            <p class="text-sm text-warm-gray">Belum ada tips.</p>
                            <button @click="openCreateModal()" class="text-sm text-sage-green font-medium hover:underline mt-1 inline-block">Tambah tips</button>
                        </td>
                    </tr>
                    @endforelse
                    <tr x-show="items.length > 0 && filteredItems.length === 0"
                        class="border-t border-amber-100/60">
                        <td colspan="7" class="px-5 py-12 text-center">
                            <div class="w-14 h-14 bg-cream rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <p class="text-sm text-warm-gray">Tidak ada tips yang cocok dengan filter.</p>
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
                    <h2 class="font-playfair text-lg text-dark-oak" x-text="isEditing ? 'Edit Tips' : 'Tambah Tips'"></h2>
                    <button @click="closeModal()" class="w-8 h-8 rounded-lg text-warm-gray hover:text-dark-oak hover:bg-cream/50 transition-all flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-6 max-h-[75vh] overflow-y-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                        <div class="lg:col-span-3">
                            <form :action="editUrl" method="POST" enctype="multipart/form-data" id="form-tip-modal" class="space-y-4">
                                @csrf
                                <input type="hidden" name="_method" :value="submitMethod">
                                <input type="hidden" name="_edit_id" :value="isEditing ? editingItem.id : ''">

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-1">Icon</label>
                                    <div class="grid grid-cols-7 sm:grid-cols-9 gap-2 mb-1">
                                        @php $icons = \App\Models\Tip::iconOptions(); @endphp
                                        @foreach($icons as $key)
                                        <button type="button" @click="form.icon = '{{ $key }}'"
                                            class="w-10 h-10 rounded-lg border-2 flex items-center justify-center transition-all"
                                            :class="form.icon === '{{ $key }}' ? 'border-sage-green bg-sage-green/10 ring-2 ring-sage-green/30' : 'border-sand hover:border-sage-green hover:bg-sage-green/5'"
                                            title="{{ $key }}">
                                            <x-icons.tip :icon="$key" class="w-5 h-5 text-dark-oak" />
                                        </button>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="icon" :value="form.icon">
                                    @error('icon')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-1">Judul Tips</label>
                                    <input type="text" name="title" x-model="form.title" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                                    @error('title')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-1">Konten</label>
                                    <textarea name="content" rows="6" x-model="form.content" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20"></textarea>
                                    @error('content')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-dark-oak mb-2">Gambar Background</label>

                                    <div x-show="isEditing && form.currentBgUrl && !form.bgPreview" class="mb-3 flex items-center gap-4 p-3 bg-cream rounded-xl border border-amber-100">
                                        <div class="w-24 h-16 rounded-xl bg-cream overflow-hidden flex-shrink-0 shadow-sm"><img :src="form.currentBgUrl" alt="Bg saat ini" class="w-full h-full object-contain p-1"></div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-medium text-dark-oak">Gambar saat ini</p>
                                            <label class="flex items-center gap-1.5 mt-2 cursor-pointer">
                                                <input type="checkbox" name="remove_background_image" value="1" class="rounded border-sand text-terracotta">
                                                <span class="text-xs text-terracotta">Hapus gambar ini</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div @dragover.prevent="dropZoneActive = true"
                                         @dragleave.prevent="dropZoneActive = false"
                                         @drop.prevent="handleBgDrop($event)"
                                         :class="dropZoneActive ? 'border-sage-green bg-sage-green/5' : ''"
                                         class="relative border-2 border-dashed border-sand rounded-2xl p-6 text-center cursor-pointer hover:border-sage-green hover:bg-sage-green/5 transition-all group"
                                         @click="$refs.bgInput.click()">
                                        <div x-show="!form.bgPreview && !(isEditing && form.currentBgUrl)">
                                            <div class="w-12 h-12 rounded-full bg-cream flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                                <svg class="w-6 h-6 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                            <p class="text-sm font-medium text-dark-oak" x-text="isEditing ? 'Pilih gambar baru untuk mengganti' : 'Klik atau drag & drop gambar di sini'"></p>
                                            <p class="text-xs text-warm-gray mt-1">PNG, JPG, WEBP — Otomatis dikompres</p>
                                        </div>
                                        <div x-show="form.bgPreview || (isEditing && form.currentBgUrl)">
                                            <img :src="form.bgPreview || form.currentBgUrl" alt="Preview" class="mx-auto max-h-48 rounded-xl object-contain shadow">
                                            <p class="text-xs text-warm-gray mt-2 truncate" x-text="form.bgName || 'Gambar saat ini'"></p>
                                            <button type="button" @click.stop="clearBg()" class="mt-2 text-xs text-terracotta hover:underline">Hapus gambar</button>
                                        </div>
                                        <input type="file" x-ref="bgInput" name="background_image" accept="image/*" class="hidden" @change="handleBgUpload">
                                    </div>
                                    @error('background_image')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-dark-oak mb-1">Urutan</label>
                                        <input type="number" name="order" x-model="form.order" min="0" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-dark-oak mb-1">Status</label>
                                        <label class="flex items-center gap-2 mt-2.5">
                                            <input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="rounded border-sand text-sage-green focus:ring-sage-green">
                                            <span class="text-sm text-dark-oak">Aktif</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="flex gap-3 pt-2">
                                    <button type="button" @click.prevent="openConfirm('save', 'Simpan Tips', isEditing ? 'Simpan perubahan tips?' : 'Simpan tips baru?', '', 'form-tip-modal')" class="bg-sage-green text-white rounded-xl px-6 py-2.5 font-medium hover:brightness-110 transition-all" x-text="isEditing ? 'Simpan Perubahan' : 'Simpan'"></button>
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
                                        <span class="text-[10px] px-2 py-0.5 rounded-full" :class="form.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'" x-text="form.is_active ? 'Aktif' : 'Nonaktif'"></span>
                                    </div>

                                    {{-- Tips card --}}
                                    <div class="relative p-6 rounded-2xl border-2 overflow-hidden group max-w-sm mx-auto border-black/20 hover:border-black/50">
                                        <template x-if="form.bgPreview || form.currentBgUrl">
                                            <div>
                                                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-105" :style="'background-image: url(' + (form.bgPreview || form.currentBgUrl) + ')'"></div>
                                                <div class="absolute inset-0 bg-gradient-to-r from-dark-oak/80 to-dark-oak/60"></div>
                                            </div>
                                        </template>
                                        <div class="relative z-10 flex items-center gap-4 mb-3">
                                            <div class="w-12 h-12 rounded-full flex items-center justify-center"
                                                 :class="form.bgPreview || form.currentBgUrl ? 'bg-white/20' : 'bg-sage-green/10'">
                                                <div :class="(form.bgPreview || form.currentBgUrl) ? 'text-white' : 'text-sage-green'">
                                                    <x-icons.tip icon="lightbulb" class="w-5 h-5" x-bind:icon="form.icon" />
                                                </div>
                                            </div>
                                            <h3 class="font-playfair text-lg" :class="form.bgPreview || form.currentBgUrl ? 'text-white' : 'text-dark-oak'"
                                                x-text="form.title || 'Judul Tips'"></h3>
                                        </div>
                                        <p class="text-sm leading-relaxed relative z-10"
                                           :class="form.bgPreview || form.currentBgUrl ? 'text-white/90' : 'text-warm-gray'"
                                           x-text="form.content || 'Konten tips akan tampil di sini...'"></p>
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
</div>
@endsection
