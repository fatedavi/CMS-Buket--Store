@extends('layouts.app')

@section('content')
<section class="min-h-[80vh] flex items-center justify-center py-16 bg-linen">
    <div class="w-full max-w-md mx-auto px-4">
        <div class="bg-white rounded-3xl border border-amber-100 p-8 shadow-sm">
            <div class="text-center mb-8">
                <h1 class="font-playfair text-3xl text-dark-oak">Masuk</h1>
                <p class="text-warm-gray text-sm mt-2">Masuk untuk fitur lengkap</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-dark-oak mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20" required autofocus>
                    @error('email')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-oak mb-1">Password</label>
                    <input type="password" name="password" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20" required>
                    @error('password')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-warm-gray">
                        <input type="checkbox" name="remember" class="rounded border-sand text-sage-green focus:ring-sage-green"> Ingat saya
                    </label>
                </div>
                <button type="submit" class="w-full bg-sage-green text-white rounded-xl py-3 font-medium hover:brightness-110 transition-all">Masuk</button>
            </form>

            <p class="text-center text-sm text-warm-gray mt-6">
                Belum punya akun? <a href="{{ route('register') }}" class="text-sage-green hover:underline">Daftar</a>
            </p>
        </div>
    </div>
</section>
@endsection
