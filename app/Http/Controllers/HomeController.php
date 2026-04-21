<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProducts = [
            ['name'=>'Pink Romance','slug'=>'pink-romance','category'=>'Wisuda','badge'=>'Bestseller','image'=>'https://images.unsplash.com/photo-1487530811176-3780de880c2d?w=600'],
            ['name'=>'Cream Elegance','slug'=>'cream-elegance','category'=>'Anniversary','badge'=>null,'image'=>'https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=600'],
            ['name'=>'Garden Fresh','slug'=>'garden-fresh','category'=>'Ulang Tahun','badge'=>'Baru','image'=>'https://images.unsplash.com/photo-1490750967868-88df5691cc45?w=600'],
            ['name'=>'Terracotta Bloom','slug'=>'terracotta-bloom','category'=>'Wisuda','badge'=>'Populer','image'=>'https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=600'],
            ['name'=>'Lavender Dream','slug'=>'lavender-dream','category'=>'Anniversary','badge'=>null,'image'=>'https://images.unsplash.com/photo-1508610048659-a06b669e3321?w=600'],
            ['name'=>'Sunny Daisy','slug'=>'sunny-daisy','category'=>'Ulang Tahun','badge'=>'Baru','image'=>'https://images.unsplash.com/photo-1463936575829-25148e1db1b8?w=600'],
            ['name'=>'White Purity','slug'=>'white-purity','category'=>'Wedding','badge'=>null,'image'=>'https://images.unsplash.com/photo-1593698054589-22f60f85bced?w=600'],
            ['name'=>'Forest Sage','slug'=>'forest-sage','category'=>'Wisuda','badge'=>'Bestseller','image'=>'https://images.unsplash.com/photo-1420593248178-d88870618ca0?w=600'],
            ['name'=>'Blush Peony','slug'=>'blush-peony','category'=>'Anniversary','badge'=>null,'image'=>'https://images.unsplash.com/photo-1455659817273-f96807779a8a?w=600'],
        ];

        $categories = [
            ['name'=>'Wisuda','slug'=>'wisuda','count'=>12,'image'=>'https://images.unsplash.com/photo-1487530811176-3780de880c2d?w=400'],
            ['name'=>'Anniversary','slug'=>'anniversary','count'=>8,'image'=>'https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=400'],
            ['name'=>'Ulang Tahun','slug'=>'ulang-tahun','count'=>15,'image'=>'https://images.unsplash.com/photo-1490750967868-88df5691cc45?w=400'],
            ['name'=>'Wedding','slug'=>'wedding','count'=>6,'image'=>'https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=400'],
            ['name'=>'Custom','slug'=>'custom','count'=>20,'image'=>'https://images.unsplash.com/photo-1508610048659-a06b669e3321?w=400'],
        ];

        $testimonials = [
            ['name'=>'Siti Rahma','location'=>'Sidoarjo','text'=>'Buketnya cantik banget, persis seperti foto. Pengiriman tepat waktu dan bunga masih segar!','rating'=>5],
            ['name'=>'Dewi Anggraini','location'=>'Surabaya','text'=>'Sudah 3x pesan untuk wisuda teman, selalu puas. Harganya worth it banget!','rating'=>5],
            ['name'=>'Maya Putri','location'=>'Sidoarjo','text'=>'Pelayanannya ramah, mau diskusi dulu sebelum bikin. Hasilnya melampaui ekspektasi.','rating'=>5],
        ];

        return view('pages.home', compact('featuredProducts', 'categories', 'testimonials'));
    }
}
