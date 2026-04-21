<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $articles = [
            ['title'=>'5 Tips Memilih Buket Bunga untuk Wisuda','slug'=>'tips-buket-wisuda','excerpt'=>'Memilih buket bunga wisuda yang tepat bukan perkara mudah. Berikut 5 tips yang bisa membantu Anda...','date'=>'15 April 2025','category'=>'Tips & Trik','image'=>'https://images.unsplash.com/photo-1487530811176-3780de880c2d?w=600'],
            ['title'=>'Warna Bunga yang Cocok untuk Anniversary','slug'=>'warna-bunga-anniversary','excerpt'=>'Setiap warna bunga memiliki makna tersendiri. Pilih yang paling mewakili perasaan Anda...','date'=>'10 April 2025','category'=>'Panduan','image'=>'https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=600'],
            ['title'=>'Tren Buket Bunga 2025','slug'=>'tren-buket-2025','excerpt'=>'Apa saja tren rangkaian bunga yang sedang populer tahun ini? Simak ulasannya...','date'=>'5 April 2025','category'=>'Inspirasi','image'=>'https://images.unsplash.com/photo-1490750967868-88df5691cc45?w=600'],
            ['title'=>'Cara Merawat Buket Bunga Agar Tahan Lama','slug'=>'cara-merawat-buket','excerpt'=>'Agar buket bunga tetap segar dan indah, ada beberapa cara perawatan yang perlu diperhatikan...','date'=>'1 April 2025','category'=>'Tips & Trik','image'=>'https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=600'],
            ['title'=>'Buket意思 — Makna Bunga dalam Budaya','slug'=>'makna-bunga','excerpt'=>'Setiap bunga memiliki makna khusus dalam berbagai budaya. Ketahui sebelum memilih...','date'=>'28 Maret 2025','category'=>'Panduan','image'=>'https://images.unsplash.com/photo-1508610048659-a06b669e3321?w=600'],
            ['title'=>'Ide Buket untuk Setiap Momen Spesial','slug'=>'ide-buket-momen','excerpt'=>'Dari wisuda hingga anniversary, temukan ide buket yang sempurna...','date'=>'25 Maret 2025','category'=>'Inspirasi','image'=>'https://images.unsplash.com/photo-1463936575829-25148e1db1b8?w=600'],
        ];

        return view('pages.blog.index', compact('articles'));
    }

    public function show(string $slug): View
    {
        $article = [
            'title'=>'5 Tips Memilih Buket Bunga untuk Wisuda',
            'slug'=>$slug,
            'date'=>'15 April 2025',
            'category'=>'Tips & Trik',
            'image'=>'https://images.unsplash.com/photo-1487530811176-3780de880c2d?w=1200',
            'content'=>'<h2>1. Sesuaikan dengan Warna Toga</h2><p>Saat memilih buket untuk wisuda, pertimbangkan warna toga yang dikenakan. Warna yang serasi akan membuat foto-foto semakin indah dan berkesan.</p><h2>2. Pilih Bunga yang Tahan Lama</h2><p>Pastikan bunga yang dipilih tetap segar hingga beberapa hari setelah acara. Bunga seperti mawar, lily, dan chrysanthemum dikenal tahan lama.</p><h2>3. Perhatikan Ukuran Buket</h2><p>Ukuran buket harus proporsional dengan tubuh penerima. Buket yang terlalu besar akan menyulitkan saat foto maupun saat memegang ijazah.</p><h2>4. Sesuaikan Budget</h2><p>Tentukan budget sejak awal. Banyak pilihan buket dengan harga bervariasi yang tetap cantik dan berkesan.</p><h2>5. Pesan dari Toko Terpercaya</h2><p>Pilih toko dengan reputasi baik agar kualitas bunga terjamin dan pengiriman tepat waktu.</p>',
        ];

        $related = [
            ['title'=>'Warna Bunga yang Cocok untuk Anniversary','slug'=>'warna-bunga-anniversary','image'=>'https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=400'],
            ['title'=>'Tren Buket Bunga 2025','slug'=>'tren-buket-2025','image'=>'https://images.unsplash.com/photo-1490750967868-88df5691cc45?w=400'],
            ['title'=>'Cara Merawat Buket Bunga Agar Tahan Lama','slug'=>'cara-merawat-buket','image'=>'https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=400'],
        ];

        return view('pages.blog.show', compact('article', 'related'));
    }
}
