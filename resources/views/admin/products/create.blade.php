@extends('layouts.admin')

@section('content')
<h1 class="font-playfair text-2xl text-dark-oak mb-6">Tambah Produk Baru</h1>

<div class="bg-white rounded-2xl border border-amber-100 p-6">
    <form class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Nama Produk</label>
            <input type="text" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Slug</label>
            <input type="text" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
            <p class="text-xs text-warm-gray mt-1">Akan dibuat otomatis dari nama produk</p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Kategori</label>
            <select class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                <option>Wisuda</option>
                <option>Anniversary</option>
                <option>Ulang Tahun</option>
                <option>Wedding</option>
                <option>Custom</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Deskripsi</label>
            <textarea rows="5" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20"></textarea>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Upload Gambar</label>
            <div class="border-2 border-dashed border-sand rounded-2xl p-8 text-center">
                <p class="text-warm-gray text-sm">Drag foto ke sini atau klik untuk pilih</p>
            </div>
        </div>
        
        <div class="flex gap-3 pt-4">
            <button type="button" class="bg-sage-green text-white rounded-xl px-6 py-2.5 font-medium">Simpan Produk</button>
            <a href="{{ route('admin.products') }}" class="border border-sand text-dark-oak rounded-xl px-6 py-2.5 font-medium">Batal</a>
        </div>
    </form>
</div>
@endsection