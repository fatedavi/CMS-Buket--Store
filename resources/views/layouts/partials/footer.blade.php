<footer class="bg-dark-oak border-t border-sage-green">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Brand -->
            <div>
                <h3 class="font-playfair text-xl text-sand mb-4">🌸 [Nama Toko]</h3>
                <p class="text-warm-gray text-sm leading-relaxed">
                    Menyediakan rangkaian bunga segar dan indah untuk setiap momen spesial Anda. Diproses dengan tangan工匠 dan pengiriman ke seluruh Sidoarjo.
                </p>
            </div>
            
            <!-- Navigation -->
            <div>
                <h4 class="text-sand font-medium mb-4">Navigasi</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-warm-gray hover:text-sage-green text-sm transition-colors">Beranda</a></li>
                    <li><a href="{{ route('catalog.index') }}" class="text-warm-gray hover:text-sage-green text-sm transition-colors">Katalog</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-warm-gray hover:text-sage-green text-sm transition-colors">Blog</a></li>
                    <li><a href="{{ route('contact') }}" class="text-warm-gray hover:text-sage-green text-sm transition-colors">Kontak</a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h4 class="text-sand font-medium mb-4">Info Kontak</h4>
                <ul class="space-y-2 text-sm text-warm-gray">
                    <li class="flex items-center gap-2">
                        <span>📍</span> Jl. [Alamat], Sidoarjo, Jawa Timur
                    </li>
                    <li class="flex items-center gap-2">
                        <span>📱</span> <a href="https://wa.me/6285649150049" class="hover:text-sage-green">085649150049</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <span>🕐</span> Senin–Sabtu: 08.00–20.00
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="border-t border-warm-gray/20 mt-8 pt-8 text-center">
            <p class="text-warm-gray text-xs">© 2025 [Nama Toko]. All rights reserved.</p>
        </div>
    </div>
</footer>
