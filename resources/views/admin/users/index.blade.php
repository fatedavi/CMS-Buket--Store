@extends('layouts.admin')

@section('content')
<div x-data="{
    search: '',
    filterRole: 'Semua',
    modalOpen: {{ $errors->any() ? 'true' : 'false' }},
    isEditing: false,
    editingItem: null,
    items: {{ $users->map(fn($u) => [
        'id' => $u->id,
        'name' => $u->name,
        'email' => $u->email,
        'is_admin' => $u->is_admin,
    ])->toJson() }},
    form: {
        name: '{{ old('name') }}',
        email: '{{ old('email') }}',
        password: '',
        is_admin: {{ old('is_admin', false) ? 'true' : 'false' }},
    },
    editUrl: '{{ old('_edit_id') ? url('admin/pengguna/' . old('_edit_id')) : route('admin.users.store') }}',
    submitMethod: '{{ old('_edit_id') ? 'PUT' : 'POST' }}',

    get filteredItems() {
        const s = this.search.toLowerCase();
        return this.items.filter(i =>
            (s === '' || i.name.toLowerCase().includes(s) || i.email.toLowerCase().includes(s))
            && (this.filterRole === 'Semua'
                || (this.filterRole === 'Admin' && i.is_admin)
                || (this.filterRole === 'Pelanggan' && !i.is_admin))
        );
    },

    openCreateModal() {
        this.isEditing = false;
        this.editingItem = null;
        this.form = { name: '', email: '', password: '', is_admin: false };
        this.editUrl = '{{ route('admin.users.store') }}';
        this.submitMethod = 'POST';
        this.modalOpen = true;
    },
    openEditModal(item) {
        this.isEditing = true;
        this.editingItem = item;
        this.form = { name: item.name, email: item.email, password: '', is_admin: item.is_admin };
        this.editUrl = '/admin/pengguna/' + item.id;
        this.submitMethod = 'PUT';
        this.modalOpen = true;
    },
    closeModal() {
        this.modalOpen = false;
        this.form = { name: '', email: '', password: '', is_admin: false };
        this.editingItem = null;
    },
}">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="font-playfair text-2xl text-dark-oak">Pengguna</h1>
            <p class="text-sm text-warm-gray mt-0.5">Kelola pengguna {{ setting('store_name', 'Toko Bunga') }}</p>
        </div>
        <button @click="openCreateModal()" class="bg-sage-green text-white rounded-xl px-4 py-2.5 text-sm font-medium hover:brightness-110 transition-all inline-flex items-center gap-1.5 shadow-sm shadow-sage-green/20 w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Pengguna
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-amber-100 p-4 mb-6 flex flex-wrap gap-3 shadow-sm">
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <svg class="w-4 h-4 text-warm-gray absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" x-model="search" placeholder="Cari pengguna..." class="w-full border border-amber-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-sage-green focus:ring-2 focus:ring-sage-green/20 transition-all">
            </div>
        </div>
        <select x-model="filterRole" class="border border-amber-200 rounded-xl px-4 py-2.5 text-sm text-warm-gray focus:outline-none focus:border-sage-green focus:ring-2 focus:ring-sage-green/20 bg-white">
            <option value="Semua">Semua Role</option>
            <option value="Admin">Admin</option>
            <option value="Pelanggan">Pelanggan</option>
        </select>
    </div>

    {{-- Mobile Cards --}}
    <div class="block md:hidden space-y-3 mb-6">
        @forelse($users as $user)
        <div x-show="filteredItems.some(i => i.name === '{{ $user->name }}' && i.email === '{{ $user->email }}')"
             class="bg-white rounded-xl border border-amber-100 p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-sage-green/20 flex items-center justify-center text-sm font-semibold text-sage-green flex-shrink-0">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-medium text-dark-oak truncate">{{ $user->name }}</h3>
                    <p class="text-xs text-warm-gray truncate">{{ $user->email }}</p>
                    <div class="flex items-center gap-2 mt-1.5">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium {{ $user->is_admin ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                            {{ $user->is_admin ? 'Admin' : 'Pelanggan' }}
                        </span>
                        <span class="text-[11px] text-warm-gray">{{ $user->created_at->format('j F Y') }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-1 flex-shrink-0">
                    <button @click="openEditModal({id: {{ $user->id }}, name: '{{ addslashes($user->name) }}', email: '{{ $user->email }}', is_admin: {{ $user->is_admin ? 'true' : 'false' }}})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-warm-gray hover:text-sage-green hover:bg-sage-green/10 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    @if($user->id !== auth()->id())
                    <button type="button" @click.prevent="openConfirm('delete', 'Hapus Pengguna', 'Hapus pengguna ini?', '', 'user-{{ $user->id }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-warm-gray hover:text-terracotta hover:bg-terracotta/10 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-amber-100 p-6 text-center">
            <div class="w-12 h-12 bg-cream rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
            </div>
            <p class="text-sm text-warm-gray">Belum ada pengguna.</p>
        </div>
        @endforelse
        <div x-show="items.length > 0 && filteredItems.length === 0"
             class="bg-white rounded-xl border border-amber-100 p-6 text-center">
            <div class="w-12 h-12 bg-cream rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <p class="text-sm text-warm-gray">Tidak ada pengguna yang cocok dengan filter.</p>
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white rounded-2xl border border-amber-100 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-cream/50">
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Nama</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Email</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Role</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Bergabung</th>
                        <th class="px-5 py-3.5 text-left text-[11px] text-warm-gray font-medium uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="border-t border-amber-100/60 hover:bg-cream/30 transition-colors"
                        x-show="filteredItems.some(i => i.name === '{{ $user->name }}' && i.email === '{{ $user->email }}')">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-sage-green/20 flex items-center justify-center text-xs font-semibold text-sage-green">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span class="text-sm text-dark-oak font-medium">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-warm-gray">{{ $user->email }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-medium {{ $user->is_admin ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                                {{ $user->is_admin ? 'Admin' : 'Pelanggan' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-warm-gray">{{ $user->created_at->format('j F Y') }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1">
                                <button @click="openEditModal({id: {{ $user->id }}, name: '{{ addslashes($user->name) }}', email: '{{ $user->email }}', is_admin: {{ $user->is_admin ? 'true' : 'false' }}})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-warm-gray hover:text-sage-green hover:bg-sage-green/10 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" id="delete-form-user-{{ $user->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click.prevent="openConfirm('delete', 'Hapus Pengguna', 'Hapus pengguna ini?', '', 'user-{{ $user->id }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-warm-gray hover:text-terracotta hover:bg-terracotta/10 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="border-t border-amber-100/60">
                        <td colspan="5" class="px-5 py-12 text-center">
                            <div class="w-14 h-14 bg-cream rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                            </div>
                            <p class="text-sm text-warm-gray">Belum ada pengguna.</p>
                        </td>
                    </tr>
                    @endforelse
                    <tr x-show="items.length > 0 && filteredItems.length === 0"
                        class="border-t border-amber-100/60">
                        <td colspan="5" class="px-5 py-12 text-center">
                            <div class="w-14 h-14 bg-cream rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <p class="text-sm text-warm-gray">Tidak ada pengguna yang cocok dengan filter.</p>
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
            <div @click.stop class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-auto my-auto"
                 x-show="modalOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                <div class="flex items-center justify-between p-6 border-b border-amber-100">
                    <h2 class="font-playfair text-lg text-dark-oak" x-text="isEditing ? 'Edit Pengguna' : 'Tambah Pengguna'"></h2>
                    <button @click="closeModal()" class="w-8 h-8 rounded-lg text-warm-gray hover:text-dark-oak hover:bg-cream/50 transition-all flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-6">
                    <form :action="editUrl" method="POST" id="form-user-modal" class="space-y-4">
                        @csrf
                        <input type="hidden" name="_method" :value="submitMethod">
                        <input type="hidden" name="_edit_id" :value="isEditing ? editingItem.id : ''">
                        <div>
                            <label class="block text-sm font-medium text-dark-oak mb-1">Nama</label>
                            <input type="text" name="name" x-model="form.name" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20" required>
                            @error('name')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-dark-oak mb-1">Email</label>
                            <input type="email" name="email" x-model="form.email" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20" required>
                            @error('email')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-dark-oak mb-1">
                                Password
                                <span x-show="isEditing" class="text-warm-gray font-normal">(kosongkan jika tidak diubah)</span>
                            </label>
                            <input type="password" name="password" x-model="form.password" :required="!isEditing" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                            @error('password')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="flex items-center gap-2 text-sm text-dark-oak">
                                <input type="checkbox" name="is_admin" value="1" x-model="form.is_admin" class="rounded border-sand text-sage-green focus:ring-sage-green"> Admin
                            </label>
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button type="button" @click.prevent="openConfirm('save', isEditing ? 'Simpan Perubahan' : 'Simpan Pengguna', isEditing ? 'Simpan perubahan pengguna?' : 'Simpan pengguna baru?', '', 'form-user-modal')" class="bg-sage-green text-white rounded-xl px-6 py-2.5 font-medium hover:brightness-110 transition-all" x-text="isEditing ? 'Simpan Perubahan' : 'Simpan'"></button>
                            <button type="button" @click="closeModal()" class="border border-sand text-dark-oak rounded-xl px-6 py-2.5 font-medium hover:bg-cream/50 transition-all">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
