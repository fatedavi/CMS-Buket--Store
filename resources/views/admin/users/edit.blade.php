@extends('layouts.admin')

@section('content')
<h1 class="font-playfair text-2xl text-dark-oak mb-6">Edit Pengguna</h1>

<div class="bg-white rounded-2xl border border-amber-100 p-6">
    <form action="{{ route('admin.users.update', $user) }}" method="POST" id="form-user-edit-{{ $user->id }}" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20" required>
            @error('name')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20" required>
            @error('email')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Password <span class="text-warm-gray font-normal">(kosongkan jika tidak diubah)</span></label>
            <input type="password" name="password" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
            @error('password')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="flex items-center gap-2 text-sm text-dark-oak">
                <input type="checkbox" name="is_admin" value="1" {{ $user->is_admin ? 'checked' : '' }} class="rounded border-sand text-sage-green focus:ring-sage-green"> Admin
            </label>
        </div>
        <div class="flex gap-3 pt-4">
            <button type="button" @click.prevent="openConfirm('save', 'Simpan Perubahan', 'Simpan perubahan pengguna?', '', 'form-user-edit-{{ $user->id }}')" class="bg-sage-green text-white rounded-xl px-6 py-2.5 font-medium hover:brightness-110 transition-all">Simpan Perubahan</button>
            <a href="{{ route('admin.users') }}" class="border border-sand text-dark-oak rounded-xl px-6 py-2.5 font-medium">Batal</a>
            @if($user->id !== auth()->id())
            <button type="button" @click.prevent="openConfirm('delete', 'Hapus Pengguna', 'Hapus pengguna ini? Tindakan ini tidak dapat dibatalkan.', '', 'user-{{ $user->id }}')" class="ml-auto border border-terracotta/30 text-terracotta rounded-xl px-6 py-2.5 font-medium hover:bg-terracotta/5 transition-all">Hapus</button>
            @endif
        </div>
    </form>

    @if($user->id !== auth()->id())
    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" id="delete-form-user-{{ $user->id }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endif
</div>
@endsection
