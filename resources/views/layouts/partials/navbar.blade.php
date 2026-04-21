<nav x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-50 bg-linen border-b border-amber-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="font-playfair text-2xl text-dark-oak hover:text-sage-green transition-colors">
                    🌸 [Nama Toko]
                </a>
            </div>
            <div class="hidden md:flex space-x-8 items-center">
                <a href="{{ route('home') }}" class="text-warm-gray hover:text-sage-green text-sm transition-colors {{ request()->routeIs('home') ? 'text-sage-green font-medium' : '' }}">Beranda</a>
                <a href="{{ route('catalog.index') }}" class="text-warm-gray hover:text-sage-green text-sm transition-colors {{ request()->routeIs('catalog.*') ? 'text-sage-green font-medium' : '' }}">Katalog</a>
                <a href="{{ route('blog.index') }}" class="text-warm-gray hover:text-sage-green text-sm transition-colors {{ request()->routeIs('blog.*') ? 'text-sage-green font-medium' : '' }}">Blog</a>
                <a href="{{ route('contact') }}" class="text-warm-gray hover:text-sage-green text-sm transition-colors {{ request()->routeIs('contact') ? 'text-sage-green font-medium' : '' }}">Kontak</a>
            </div>
            <div class="hidden md:block">
                <a href="https://wa.me/6285649150049" target="_blank" class="bg-sage-green text-white rounded-full px-4 py-2 text-sm font-medium hover:brightness-110 transition-all">Pesan via WA</a>
            </div>
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-dark-oak p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-amber-100 py-4">
            <div class="flex flex-col space-y-4">
                <a href="{{ route('home') }}" class="text-warm-gray hover:text-sage-green text-sm">Beranda</a>
                <a href="{{ route('catalog.index') }}" class="text-warm-gray hover:text-sage-green text-sm">Katalog</a>
                <a href="{{ route('blog.index') }}" class="text-warm-gray hover:text-sage-green text-sm">Blog</a>
                <a href="{{ route('contact') }}" class="text-warm-gray hover:text-sage-green text-sm">Kontak</a>
                <a href="https://wa.me/6285649150049" target="_blank" class="bg-sage-green text-white rounded-full px-4 py-2 text-sm text-center">Pesan via WA</a>
            </div>
        </div>
    </div>
</nav>
