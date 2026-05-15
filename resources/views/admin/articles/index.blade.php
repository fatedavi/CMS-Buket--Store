@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="font-playfair text-2xl text-dark-oak">Artikel</h1>
    <a href="{{ route('admin.articles.create') }}" class="bg-sage-green text-white rounded-xl px-4 py-2 text-sm hover:brightness-110 transition-all">Tambah Artikel</a>
</div>

@if($articles->count())
<div class="bg-white rounded-2xl border border-amber-100 overflow-hidden">
    <table class="w-full">
        <thead class="bg-cream">
            <tr>
                <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Gambar</th>
                <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Judul</th>
                <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Kategori</th>
                <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Tanggal</th>
                <th class="px-4 py-3 text-left text-sm text-dark-oak font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($articles as $article)
            <tr class="border-t border-amber-100 hover:bg-[#faf8f4]">
                <td class="px-4 py-3"><img src="{{ $article->image }}" class="w-12 h-12 rounded-lg object-cover"></td>
                <td class="px-4 py-3 text-sm text-dark-oak">{{ $article->title }}</td>
                <td class="px-4 py-3 text-sm text-warm-gray">{{ $article->category }}</td>
                <td class="px-4 py-3 text-sm text-warm-gray">{{ $article->date }}</td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.articles.edit', $article) }}" class="inline-block text-warm-gray hover:text-sage-green mr-2"><x-icons.pencil /></a>
                    <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="inline" onsubmit="return confirm('Hapus artikel ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-warm-gray hover:text-terracotta"><x-icons.trash /></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="bg-white rounded-2xl border border-amber-100 p-8 text-center">
    <p class="text-warm-gray">Belum ada artikel. <a href="{{ route('admin.articles.create') }}" class="text-sage-green underline">Tambah artikel</a></p>
</div>
@endif
@endsection
