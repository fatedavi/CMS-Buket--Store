@extends('layouts.app')

@section('content')
<section class="min-h-[80vh] flex items-center justify-center py-16 bg-linen">
    <div class="w-full max-w-md mx-auto px-4">
        <div class="bg-white rounded-3xl border border-amber-100 p-8 shadow-sm">
            <div class="text-center mb-8">
                <h1 class="font-playfair text-3xl text-dark-oak">Daftar</h1>
                <p class="text-warm-gray text-sm mt-2">Buat akun untuk pengalaman lebih baik</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-dark-oak mb-1">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20" required autofocus>
                    @error('name')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-oak mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20" required>
                    @error('email')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-oak mb-1">Password</label>
                    <input type="password" name="password" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20" required>
                    @error('password')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-oak mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20" required>
                </div>
                <button type="submit" class="w-full bg-sage-green text-white rounded-xl py-3 font-medium hover:brightness-110 transition-all">Daftar</button>
            </form>

            <p class="text-center text-sm text-warm-gray mt-6">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-sage-green hover:underline">Masuk</a>
            </p>
        </div>
    </div>
</section>
@endsection
