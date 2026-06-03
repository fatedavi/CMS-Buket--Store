@extends('layouts.admin')

@section('content')
<div x-data="{
    search: '',
    filterRole: 'Semua',
    items: {{ $users->map(fn($u) => [
        'name' => $u->name,
        'email' => $u->email,
        'is_admin' => $u->is_admin,
    ])->toJson() }},
    get filteredItems() {
        const s = this.search.toLowerCase();
        return this.items.filter(i =>
            (s === '' || i.name.toLowerCase().includes(s) || i.email.toLowerCase().includes(s))
            && (this.filterRole === 'Semua'
                || (this.filterRole === 'Admin' && i.is_admin)
                || (this.filterRole === 'Pelanggan' && !i.is_admin))
        );
    }
}">
    <div class="flex justify-between items-center mb-6">
        <h1 class="font-playfair text-2xl text-dark-oak">Pengguna</h1>
        <a href="{{ route('admin.users.create') }}" class="bg-sage-green text-white rounded-xl px-4 py-2 text-sm hover:brightness-110 transition-all">Tambah Pengguna</a>
    </div>

    <div class="bg-white rounded-2xl border border-amber-100 p-4 mb-6 flex flex-wrap gap-3">
        <div class="flex-1 min-w-[200px]">
            <input type="text" x-model="search" placeholder="Cari pengguna..." class="w-full border border-amber-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-sage-green">
        </div>
        <select x-model="filterRole" class="border border-amber-200 rounded-xl px-4 py-2 text-sm text-warm-gray focus:outline-none focus:border-sage-green">
            <option value="Semua">Semua Role</option>
            <option value="Admin">Admin</option>
            <option value="Pelanggan">Pelanggan</option>
        </select>
    </div>

    <div class="bg-white rounded-2xl border border-amber-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-cream">
                <tr>
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Nama</th>
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Email</th>
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Role</th>
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Bergabung</th>
                    <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-t border-amber-100 hover:bg-[#faf8f4]"
                    x-show="filteredItems.some(i => i.name === '{{ $user->name }}' && i.email === '{{ $user->email }}')">
                    <td class="px-4 py-3 text-sm text-dark-oak">{{ $user->name }}</td>
                    <td class="px-4 py-3 text-sm text-warm-gray">{{ $user->email }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs {{ $user->is_admin ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $user->is_admin ? 'Admin' : 'Pelanggan' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-warm-gray">{{ $user->created_at->format('j F Y') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.users.edit', $user) }}" class="inline-block text-warm-gray hover:text-sage-green mr-2"><x-icons.pencil /></a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengguna ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-warm-gray hover:text-terracotta"><x-icons.trash /></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr class="border-t border-amber-100">
                    <td colspan="5" class="px-4 py-8 text-center text-warm-gray text-sm">Belum ada pengguna.</td>
                </tr>
                @endforelse
                <tr x-show="items.length > 0 && filteredItems.length === 0"
                    class="border-t border-amber-100">
                    <td colspan="5" class="px-4 py-8 text-center text-warm-gray text-sm">Tidak ada pengguna yang cocok dengan filter.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection