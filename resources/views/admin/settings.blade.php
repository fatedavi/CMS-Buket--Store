@extends('layouts.admin')

@section('content')
<h1 class="font-playfair text-2xl text-dark-oak mb-6">Pengaturan</h1>

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="space-y-6">
        {{-- Info Toko --}}
        <div class="bg-white rounded-2xl border border-amber-100 p-6">
            <h2 class="font-playfair text-lg text-dark-oak mb-4">Informasi Toko</h2>
            <div class="space-y-4">
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
            </div>
        </div>

        {{-- Hero Slideshow --}}
        <div class="bg-white rounded-2xl border border-amber-100 p-6">
            <h2 class="font-playfair text-lg text-dark-oak mb-4">Hero Slideshow (Halaman Depan)</h2>
            <p class="text-xs text-warm-gray mb-4">Upload hingga 5 gambar. Akan tampil bergantian sebagai slideshow di hero homepage.</p>
            <div class="space-y-4">
                @for($i = 0; $i < 5; $i++)
                <div>
                    <label class="block text-sm font-medium text-dark-oak mb-1">Slide {{ $i + 1 }}</label>
                    <input type="file" name="hero_slide_{{ $i }}" accept="image/*" class="w-full text-sm text-warm-gray file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-sage-green/10 file:text-sage-green hover:file:bg-sage-green/20">
                    @if(!empty($settings['hero_slide_'.$i]))
                    <img src="{{ Storage::url($settings['hero_slide_'.$i]) }}" class="w-48 mt-2 rounded-lg border border-amber-100">
                    @endif
                </div>
                @endfor
            </div>
        </div>

        <button type="submit" class="bg-sage-green text-white rounded-xl px-8 py-3 font-medium hover:brightness-110 transition-all">Simpan Semua Pengaturan</button>
    </div>
</form>
@endsection
