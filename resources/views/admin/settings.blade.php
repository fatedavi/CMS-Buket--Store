@extends('layouts.admin')

@section('content')
<h1 class="font-playfair text-2xl text-dark-oak mb-6">Pengaturan</h1>

<div class="bg-white rounded-2xl border border-amber-100 p-6">
    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Nama Toko</label>
            <input type="text" name="store_name" value="{{ old('store_name', $settings['store_name'] ?? '') }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
            @error('store_name')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Nomor WhatsApp</label>
            <input type="text" name="whatsapp" value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
            @error('whatsapp')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Alamat</label>
            <textarea name="address" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">{{ old('address', $settings['address'] ?? '') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Instagram</label>
            <input type="text" name="instagram" value="{{ old('instagram', $settings['instagram'] ?? '') }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
        </div>
        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Jam Buka</label>
            <input type="text" name="hours" value="{{ old('hours', $settings['hours'] ?? '') }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
        </div>
        <button type="submit" class="bg-sage-green text-white rounded-xl px-6 py-2.5 font-medium hover:brightness-110 transition-all">Simpan</button>
    </form>
</div>
@endsection
