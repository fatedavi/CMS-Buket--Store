<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = [
            ['name'=>'Pink Romance','slug'=>'pink-romance','category'=>'Wisuda','badge'=>'Bestseller','image'=>'https://images.unsplash.com/photo-1487530811176-3780de880c2d?w=600'],
            ['name'=>'Cream Elegance','slug'=>'cream-elegance','category'=>'Anniversary','badge'=>null,'image'=>'https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=600'],
            ['name'=>'Garden Fresh','slug'=>'garden-fresh','category'=>'Ulang Tahun','badge'=>'Baru','image'=>'https://images.unsplash.com/photo-1490750967868-88df5691cc45?w=600'],
            ['name'=>'Terracotta Bloom','slug'=>'terracotta-bloom','category'=>'Wisuda','badge'=>'Populer','image'=>'https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=600'],
            ['name'=>'Lavender Dream','slug'=>'lavender-dream','category'=>'Anniversary','badge'=>null,'image'=>'https://images.unsplash.com/photo-1508610048659-a06b669e3321?w=600'],
            ['name'=>'Sunny Daisy','slug'=>'sunny-daisy','category'=>'Ulang Tahun','badge'=>'Baru','image'=>'https://images.unsplash.com/photo-1463936575829-25148e1db1b8?w=600'],
            ['name'=>'White Purity','slug'=>'white-purity','category'=>'Wedding','badge'=>null,'image'=>'https://images.unsplash.com/photo-1593698054589-22f60f85bced?w=600'],
            ['name'=>'Forest Sage','slug'=>'forest-sage','category'=>'Wisuda','badge'=>'Bestseller','image'=>'https://images.unsplash.com/photo-1420593248178-d88870618ca0?w=600'],
            ['name'=>'Blush Peony','slug'=>'blush-peony','category'=>'Anniversary','badge'=>null,'image'=>'https://images.unsplash.com/photo-1455659817273-f96807779a8a?w=600'],
            ['name'=>'Rose Gold','slug'=>'rose-gold','category'=>'Wedding','badge'=>'Baru','image'=>'https://images.unsplash.com/photo-1519378058457-4c29a0a2efac?w=600'],
            ['name'=>'Spring Meadow','slug'=>'spring-meadow','category'=>'Custom','badge'=>null,'image'=>'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=600'],
            ['name'=>'Classic Red','slug'=>'classic-red','category'=>'Anniversary','badge'=>'Bestseller','image'=>'https://images.unsplash.com/photo-1455659817273-f96807779a8a?w=600'],
        ];

        $categories = ['Semua','Wisuda','Anniversary','Ulang Tahun','Wedding','Custom'];

        return view('pages.catalog.index', compact('products', 'categories'));
    }

    public function show(string $slug): View
    {
        $products = [
            'pink-romance' => ['name'=>'Pink Romance','slug'=>'pink-romance','category'=>'Wisuda','description'=>'Rangkaian bunga mawar pink yang elegan, cocok untuk hadiah wisuda yang berkesan. Menggunakan mawar segar pilihan dengan tambahan baby breath dan daun hijau segar. Tersedia dalam ukuran medium dan large.','images'=>['https://images.unsplash.com/photo-1487530811176-3780de880c2d?w=800','https://images.unsplash.com/photo-1508610048659-a06b669e3321?w=800','https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=800','https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=800']],
            'cream-elegance' => ['name'=>'Cream Elegance','slug'=>'cream-elegance','category'=>'Anniversary','description'=>'Buket anniversary dengan nuansa cream yang mewah dan elegan.','images'=>['https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=800','https://images.unsplash.com/photo-1487530811176-3780de880c2d?w=800','https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=800']],
            'garden-fresh' => ['name'=>'Garden Fresh','slug'=>'garden-fresh','category'=>'Ulang Tahun','description'=>'Segar dan ceria, sempurna untuk ulang tahun.','images'=>['https://images.unsplash.com/photo-1490750967868-88df5691cc45?w=800','https://images.unsplash.com/photo-1508610048659-a06b669e3321?w=800']],
        ];

        $product = $products[$slug] ?? $products['pink-romance'];
        $product['whatsapp'] = '6285649150049';

        $relatedProducts = [
            ['name'=>'Lavender Dream','slug'=>'lavender-dream','category'=>'Anniversary','badge'=>null,'image'=>'https://images.unsplash.com/photo-1508610048659-a06b669e3321?w=600'],
            ['name'=>'Terracotta Bloom','slug'=>'terracotta-bloom','category'=>'Wisuda','badge'=>'Populer','image'=>'https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=600'],
            ['name'=>'Sunny Daisy','slug'=>'sunny-daisy','category'=>'Ulang Tahun','badge'=>'Baru','image'=>'https://images.unsplash.com/photo-1463936575829-25148e1db1b8?w=600'],
            ['name'=>'Forest Sage','slug'=>'forest-sage','category'=>'Wisuda','badge'=>'Bestseller','image'=>'https://images.unsplash.com/photo-1420593248178-d88870618ca0?w=600'],
        ];

        return view('pages.catalog.show', compact('product', 'relatedProducts'));
    }
}
