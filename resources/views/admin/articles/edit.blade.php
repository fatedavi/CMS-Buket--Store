@extends('layouts.admin')

@section('content')
<h1 class="font-playfair text-2xl text-dark-oak mb-6">Edit Artikel</h1>

<div class="bg-white rounded-2xl border border-amber-100 p-6">
    <form action="{{ route('admin.articles.update', $article) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Judul Artikel</label>
            <input type="text" name="title" value="{{ old('title', $article->title) }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
            @error('title')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $article->slug) }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Kategori</label>
            <select name="category" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                @foreach(['Tips & Trik','Panduan','Inspirasi'] as $cat)
                <option value="{{ $cat }}" {{ old('category', $article->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Excerpt (cuplikan singkat)</label>
            <textarea name="excerpt" rows="3" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">{{ old('excerpt', $article->excerpt) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Konten (HTML)</label>
            <textarea name="content" rows="12" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20 font-mono text-sm">{{ old('content', $article->content) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">URL Gambar</label>
            <input type="text" name="image" value="{{ old('image', $article->image) }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="bg-sage-green text-white rounded-xl px-6 py-2.5 font-medium hover:brightness-110 transition-all">Simpan Perubahan</button>
            <a href="{{ route('admin.articles') }}" class="border border-sand text-dark-oak rounded-xl px-6 py-2.5 font-medium">Batal</a>
        </div>
    </form>
</div>
@endsection
