@extends('layouts.admin')

@section('content')
<h1 class="font-playfair text-2xl text-dark-oak mb-6">Pengaturan</h1>

<div class="bg-white rounded-2xl border border-amber-100 p-6">
    <form class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Nama Toko</label>
            <input type="text" value="[Nama Toko]" class="w-full border border-sand rounded-xl px-4 py-2.5">
        </div>
        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Nomor WhatsApp</label>
            <input type="text" value="085649150049" class="w-full border border-sand rounded-xl px-4 py-2.5">
        </div>
        <div>
            <label class="block text-sm font-medium text-dark-oak mb-1">Alamat</label>
            <textarea class="w-full border border-sand rounded-xl px-4 py-2.5"></textarea>
        </div>
        <button type="button" class="bg-sage-green text-white rounded-xl px-6 py-2.5 font-medium">Simpan</button>
    </form>
</div>
@endsection