<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - {{ setting('store_name', '[Nama Toko]') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'DM Sans', sans-serif; background-color: #f5f0e8; }
    </style>
</head>
<body>
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">
        {{-- Overlay --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/40 z-30 md:hidden" style="display: none;"></div>

        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-dark-oak transform transition-transform duration-300 md:translate-x-0 md:static md:inset-auto"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="p-4 border-b border-white/10 flex items-center justify-between">
                <a href="{{ route('home') }}" class="font-playfair text-xl text-white"><x-icons.cherry-blossom class="w-5 h-5 inline-block align-middle mr-1" /> {{ setting('store_name', '[Nama Toko]') }}</a>
                <span class="text-xs bg-sage-green text-white px-2 py-0.5 rounded">Admin</span>
            </div>
            <nav class="p-4 space-y-2">
                <a href="{{ route('admin.index') }}" class="flex items-center gap-2 text-sand px-3 py-2 rounded-lg {{ request()->routeIs('admin.index') ? 'bg-sage-green text-white' : 'hover:bg-white/10' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.chat') }}" class="flex items-center gap-2 text-sand px-3 py-2 rounded-lg {{ request()->routeIs('admin.chat') ? 'bg-sage-green text-white' : 'hover:bg-white/10' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Chat
                </a>
                <a href="{{ route('admin.chat.archive') }}" class="flex items-center gap-2 text-sand px-3 py-2 rounded-lg {{ request()->routeIs('admin.chat.archive*') ? 'bg-sage-green text-white' : 'hover:bg-white/10' }} ml-4 text-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    Arsip
                </a>
                <a href="{{ route('admin.products') }}" class="flex items-center gap-2 text-sand px-3 py-2 rounded-lg {{ request()->routeIs('admin.products*') ? 'bg-sage-green text-white' : 'hover:bg-white/10' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4l-8 4"/></svg>
                    Produk
                </a>
                <a href="{{ route('admin.articles') }}" class="flex items-center gap-2 text-sand px-3 py-2 rounded-lg {{ request()->routeIs('admin.articles*') ? 'bg-sage-green text-white' : 'hover:bg-white/10' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Artikel
                </a>
                <a href="{{ route('admin.users') }}" class="flex items-center gap-2 text-sand px-3 py-2 rounded-lg {{ request()->routeIs('admin.users*') ? 'bg-sage-green text-white' : 'hover:bg-white/10' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                    Pengguna
                </a>
                <a href="{{ route('admin.categories') }}" class="flex items-center gap-2 text-sand px-3 py-2 rounded-lg {{ request()->routeIs('admin.categories*') ? 'bg-sage-green text-white' : 'hover:bg-white/10' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Kategori
                </a>
                <a href="{{ route('admin.tips') }}" class="flex items-center gap-2 text-sand px-3 py-2 rounded-lg {{ request()->routeIs('admin.tips*') ? 'bg-sage-green text-white' : 'hover:bg-white/10' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    Tips
                </a>
                <a href="{{ route('admin.settings') }}" class="flex items-center gap-2 text-sand px-3 py-2 rounded-lg {{ request()->routeIs('admin.settings') ? 'bg-sage-green text-white' : 'hover:bg-white/10' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Pengaturan
                </a>
                <div class="border-t border-white/10 my-3"></div>
                <form action="{{ route('logout') }}" method="POST" class="block">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 text-sand px-3 py-2 rounded-lg hover:bg-terracotta/20 hover:text-terracotta w-full transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Keluar
                    </button>
                </form>
            </nav>
        </aside>

        {{-- Main --}}
        <div class="flex-1 min-w-0">
            {{-- Top bar mobile --}}
            <div class="sticky top-0 z-20 bg-dark-oak md:hidden flex items-center gap-3 px-4 py-3">
                <button @click="sidebarOpen = !sidebarOpen" class="text-white p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <span class="font-playfair text-lg text-white">{{ setting('store_name', '[Nama Toko]') }}</span>
                <span class="text-[10px] bg-sage-green text-white px-2 py-0.5 rounded ml-auto">Admin</span>
            </div>

            <main class="p-4 md:p-8">
                @if(session('success'))
                    <div class="bg-sage-green/10 border border-sage-green text-sage-green rounded-xl px-4 py-3 mb-6 text-sm">{{ session('success') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
