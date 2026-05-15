@extends('layouts.admin')

@section('content')
<h1 class="font-playfair text-2xl text-dark-oak mb-6">Edit Produk</h1>

<div class="bg-white rounded-2xl border border-amber-100 p-6">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
            @error('name')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
            <p class="text-xs text-warm-gray mt-1">Kosongkan untuk membuat otomatis dari nama produk</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Kategori</label>
            <select name="category" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                @foreach(['Wisuda','Anniversary','Ulang Tahun','Wedding','Custom'] as $cat)
                <option value="{{ $cat }}" {{ old('category', $product->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            @error('category')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Deskripsi</label>
            <textarea name="description" rows="5" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">{{ old('description', $product->description) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">URL Gambar Utama</label>
            <input type="text" name="image" value="{{ old('image', $product->image) }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Badge</label>
            <select name="badge" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                <option value="">Tidak ada</option>
                @foreach(['Bestseller','Baru','Populer'] as $b)
                <option value="{{ $b }}" {{ old('badge', $product->badge) === $b ? 'selected' : '' }}>{{ $b }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Status</label>
            <select name="status" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                <option value="Aktif" {{ old('status', $product->status) === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Draft" {{ old('status', $product->status) === 'Draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="bg-sage-green text-white rounded-xl px-6 py-2.5 font-medium hover:brightness-110 transition-all">Simpan Perubahan</button>
            <a href="{{ route('admin.products') }}" class="border border-sand text-dark-oak rounded-xl px-6 py-2.5 font-medium">Batal</a>
        </div>
    </form>
</div>
@endsection
