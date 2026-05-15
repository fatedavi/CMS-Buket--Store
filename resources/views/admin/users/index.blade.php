@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="font-playfair text-2xl text-dark-oak">Pengguna</h1>
    <a href="{{ route('admin.users.create') }}" class="bg-sage-green text-white rounded-xl px-4 py-2 text-sm hover:brightness-110 transition-all">Tambah Pengguna</a>
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
            @foreach($users as $user)
            <tr class="border-t border-amber-100 hover:bg-[#faf8f4]">
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
            @endforeach
        </tbody>
    </table>
</div>
@endsection
