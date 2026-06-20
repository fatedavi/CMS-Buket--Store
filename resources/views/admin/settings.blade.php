@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="font-playfair text-2xl text-dark-oak">Pengaturan</h1>
    <p class="text-sm text-warm-gray mt-1">Konfigurasi {{ setting('store_name', 'Toko Bunga') }}</p>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="form-settings">
    @csrf
    <div class="space-y-6">
        {{-- Info Toko --}}
        <div class="bg-white rounded-2xl border border-amber-100 p-6 shadow-sm">
            <div class="flex items-center gap-2.5 mb-5 pb-4 border-b border-amber-100">
                <div class="w-8 h-8 rounded-lg bg-sage-green/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-sage-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h2 class="font-playfair text-lg text-dark-oak">Informasi Toko</h2>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-dark-oak mb-1">Nama Toko</label>
                    <input type="text" name="store_name" value="{{ old('store_name', $settings['store_name'] ?? '') }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20 transition-all">
                    @error('store_name')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-oak mb-1">
                            <svg class="w-3.5 h-3.5 inline-block align-middle mr-1 text-sage-green" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Nomor WhatsApp
                        </label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20 transition-all">
                        @error('whatsapp')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-oak mb-1">
                            <svg class="w-3.5 h-3.5 inline-block align-middle mr-1 text-[#E4405F]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            Instagram
                        </label>
                        <input type="text" name="instagram" value="{{ old('instagram', $settings['instagram'] ?? '') }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-oak mb-1">
                        <svg class="w-3.5 h-3.5 inline-block align-middle mr-1 text-warm-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Alamat
                    </label>
                    <textarea name="address" rows="3" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20 transition-all">{{ old('address', $settings['address'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-oak mb-1">
                        <svg class="w-3.5 h-3.5 inline-block align-middle mr-1 text-warm-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Jam Buka
                    </label>
                    <input type="text" name="hours" value="{{ old('hours', $settings['hours'] ?? '') }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20 transition-all">
                </div>
            </div>
        </div>

        {{-- Hero Slideshow --}}
        <div class="bg-white rounded-2xl border border-amber-100 p-6 shadow-sm">
            <div class="flex items-center gap-2.5 mb-5 pb-4 border-b border-amber-100">
                <div class="w-8 h-8 rounded-lg bg-blush/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h2 class="font-playfair text-lg text-dark-oak">Hero Slideshow</h2>
            </div>
            <p class="text-xs text-warm-gray mb-5">Upload hingga 5 gambar yang akan tampil bergantian sebagai slideshow di hero homepage. Rekomendasi ukuran: 1920x800px.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @for($i = 0; $i < 5; $i++)
                <div class="border border-dashed border-sand rounded-xl p-4 hover:border-sage-green/50 transition-colors">
                    <label class="block text-xs font-medium text-warm-gray mb-2">Slide {{ $i + 1 }}</label>
                    <input type="file" name="hero_slide_{{ $i }}" accept="image/*" class="w-full text-sm text-warm-gray file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-sage-green/10 file:text-sage-green hover:file:bg-sage-green/20 transition-all">
                    @if(!empty($settings['hero_slide_'.$i]))
                    <div class="mt-2 rounded-lg overflow-hidden border border-amber-100 bg-cream">
                        <img src="{{ Storage::url($settings['hero_slide_'.$i]) }}" class="w-full h-24 object-contain p-1">
                    </div>
                    @endif
                </div>
                @endfor
            </div>
        </div>

        {{-- Chat Archive Settings --}}
        <div class="bg-white rounded-2xl border border-amber-100 p-6 shadow-sm">
            <div class="flex items-center gap-2.5 mb-5 pb-4 border-b border-amber-100">
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <h2 class="font-playfair text-lg text-dark-oak">Pengaturan Chat & Arsip</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-dark-oak mb-1">Tutup Otomatis (jam)</label>
                    <input type="number" name="chat_auto_close_hours" value="{{ old('chat_auto_close_hours', $settings['chat_auto_close_hours'] ?? '3') }}" min="1" max="365"
                           class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20 transition-all">
                    <p class="text-xs text-warm-gray mt-1">Percakapan tanpa balasan customer akan ditutup otomatis setelah X jam.</p>
                    @error('chat_auto_close_hours')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-oak mb-1">Hapus Arsip (hari)</label>
                    <input type="number" name="chat_prune_days" value="{{ old('chat_prune_days', $settings['chat_prune_days'] ?? '30') }}" min="1" max="365"
                           class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20 transition-all">
                    <p class="text-xs text-warm-gray mt-1">Percakapan yang sudah ditutup akan dihapus otomatis setelah X hari.</p>
                    @error('chat_prune_days')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="button" @click.prevent="openConfirm('save', 'Simpan Pengaturan', 'Simpan semua pengaturan?', '', 'form-settings')" class="bg-sage-green text-white rounded-xl px-8 py-3 font-medium hover:brightness-110 transition-all shadow-sm shadow-sage-green/20 inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Semua Pengaturan
            </button>
        </div>
    </div>
</form>
@endsection