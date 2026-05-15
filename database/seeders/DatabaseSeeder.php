<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AdminSeeder::class);

        $products = [
            ['name' => 'Pink Romance', 'slug' => 'pink-romance', 'category' => 'Wisuda', 'badge' => 'Bestseller', 'description' => 'Rangkaian bunga mawar pink yang elegan, cocok untuk hadiah wisuda yang berkesan. Menggunakan mawar segar pilihan dengan tambahan baby breath dan daun hijau segar. Tersedia dalam ukuran medium dan large.', 'image' => 'https://images.unsplash.com/photo-1487530811176-3780de880c2d?w=600', 'images' => ['https://images.unsplash.com/photo-1487530811176-3780de880c2d?w=800', 'https://images.unsplash.com/photo-1508610048659-a06b669e3321?w=800', 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=800', 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=800']],
            ['name' => 'Cream Elegance', 'slug' => 'cream-elegance', 'category' => 'Anniversary', 'badge' => null, 'description' => 'Buket anniversary dengan nuansa cream yang mewah dan elegan.', 'image' => 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=600', 'images' => ['https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=800', 'https://images.unsplash.com/photo-1487530811176-3780de880c2d?w=800', 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=800']],
            ['name' => 'Garden Fresh', 'slug' => 'garden-fresh', 'category' => 'Ulang Tahun', 'badge' => 'Baru', 'description' => 'Segar dan ceria, sempurna untuk ulang tahun.', 'image' => 'https://images.unsplash.com/photo-1490750967868-88df5691cc45?w=600', 'images' => ['https://images.unsplash.com/photo-1490750967868-88df5691cc45?w=800', 'https://images.unsplash.com/photo-1508610048659-a06b669e3321?w=800']],
            ['name' => 'Terracotta Bloom', 'slug' => 'terracotta-bloom', 'category' => 'Wisuda', 'badge' => 'Populer', 'description' => 'Buket wisuda dengan nuansa terracotta yang hangat dan modern.', 'image' => 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=600'],
            ['name' => 'Lavender Dream', 'slug' => 'lavender-dream', 'category' => 'Anniversary', 'badge' => null, 'description' => 'Buket bernuansa lavender yang romantis untuk anniversary.', 'image' => 'https://images.unsplash.com/photo-1508610048659-a06b669e3321?w=600'],
            ['name' => 'Sunny Daisy', 'slug' => 'sunny-daisy', 'category' => 'Ulang Tahun', 'badge' => 'Baru', 'description' => 'Buket ceria dengan bunga daisy kuning untuk ulang tahun.', 'image' => 'https://images.unsplash.com/photo-1463936575829-25148e1db1b8?w=600'],
            ['name' => 'White Purity', 'slug' => 'white-purity', 'category' => 'Wedding', 'badge' => null, 'description' => 'Buket putih elegan untuk momen pernikahan yang suci.', 'image' => 'https://images.unsplash.com/photo-1593698054589-22f60f85bced?w=600'],
            ['name' => 'Forest Sage', 'slug' => 'forest-sage', 'category' => 'Wisuda', 'badge' => 'Bestseller', 'description' => 'Buket dengan nuansa hijau sage yang natural dan segar.', 'image' => 'https://images.unsplash.com/photo-1420593248178-d88870618ca0?w=600'],
            ['name' => 'Blush Peony', 'slug' => 'blush-peony', 'category' => 'Anniversary', 'badge' => null, 'description' => 'Buket peony warna blush pink yang lembut dan manis.', 'image' => 'https://images.unsplash.com/photo-1455659817273-f96807779a8a?w=600'],
            ['name' => 'Rose Gold', 'slug' => 'rose-gold', 'category' => 'Wedding', 'badge' => 'Baru', 'description' => 'Buket rose gold yang mewah untuk momen istimewa.', 'image' => 'https://images.unsplash.com/photo-1519378058457-4c29a0a2efac?w=600'],
            ['name' => 'Spring Meadow', 'slug' => 'spring-meadow', 'category' => 'Custom', 'badge' => null, 'description' => 'Rangkaian bunga padang rumput segar untuk berbagai acara.', 'image' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=600'],
            ['name' => 'Classic Red', 'slug' => 'classic-red', 'category' => 'Anniversary', 'badge' => 'Bestseller', 'description' => 'Buket mawar merah klasik yang timeless untuk anniversary.', 'image' => 'https://images.unsplash.com/photo-1455659817273-f96807779a8a?w=600'],
        ];

        foreach ($products as $data) {
            $images = $data['images'] ?? null;
            unset($data['images']);
            Product::create(array_merge($data, [
                'status' => in_array($data['slug'], ['terracotta-bloom']) ? 'Draft' : 'Aktif',
                'images' => $images,
            ]));
        }

        $articles = [
            ['title' => '5 Tips Memilih Buket Bunga untuk Wisuda', 'slug' => 'tips-buket-wisuda', 'excerpt' => 'Memilih buket bunga wisuda yang tepat bukan perkara mudah. Berikut 5 tips yang bisa membantu Anda...', 'content' => '<h2>1. Sesuaikan dengan Warna Toga</h2><p>Saat memilih buket untuk wisuda, pertimbangkan warna toga yang dikenakan. Warna yang serasi akan membuat foto-foto semakin indah dan berkesan.</p><h2>2. Pilih Bunga yang Tahan Lama</h2><p>Pastikan bunga yang dipilih tetap segar hingga beberapa hari setelah acara. Bunga seperti mawar, lily, dan chrysanthemum dikenal tahan lama.</p><h2>3. Perhatikan Ukuran Buket</h2><p>Ukuran buket harus proporsional dengan tubuh penerima. Buket yang terlalu besar akan menyulitkan saat foto maupun saat memegang ijazah.</p><h2>4. Sesuaikan Budget</h2><p>Tentukan budget sejak awal. Banyak pilihan buket dengan harga bervariasi yang tetap cantik dan berkesan.</p><h2>5. Pesan dari Toko Terpercaya</h2><p>Pilih toko dengan reputasi baik agar kualitas bunga terjamin dan pengiriman tepat waktu.</p>', 'date' => '15 April 2025', 'category' => 'Tips & Trik', 'image' => 'https://images.unsplash.com/photo-1487530811176-3780de880c2d?w=600'],
            ['title' => 'Warna Bunga yang Cocok untuk Anniversary', 'slug' => 'warna-bunga-anniversary', 'excerpt' => 'Setiap warna bunga memiliki makna tersendiri. Pilih yang paling mewakili perasaan Anda...', 'content' => '<p>Memilih warna bunga untuk anniversary bukan sekadar soal estetika. Setiap warna memiliki makna mendalam yang dapat menambah kesan romantis momen spesial Anda.</p>', 'date' => '10 April 2025', 'category' => 'Panduan', 'image' => 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=600'],
            ['title' => 'Tren Buket Bunga 2025', 'slug' => 'tren-buket-2025', 'excerpt' => 'Apa saja tren rangkaian bunga yang sedang populer tahun ini? Simak ulasannya...', 'content' => '<p>Tahun 2025 menghadirkan tren buket bunga dengan gaya minimalis namun tetap elegan. Warna-warna earthy tone seperti terracotta, sage green, dan dusty pink menjadi favorit.</p>', 'date' => '5 April 2025', 'category' => 'Inspirasi', 'image' => 'https://images.unsplash.com/photo-1490750967868-88df5691cc45?w=600'],
            ['title' => 'Cara Merawat Buket Bunga Agar Tahan Lama', 'slug' => 'cara-merawat-buket', 'excerpt' => 'Agar buket bunga tetap segar dan indah, ada beberapa cara perawatan yang perlu diperhatikan...', 'content' => '<p>Buket bunga bisa bertahan lebih lama dengan perawatan yang tepat. Ganti air secara rutin, potong batang secara miring, dan hindarkan dari sinar matahari langsung.</p>', 'date' => '1 April 2025', 'category' => 'Tips & Trik', 'image' => 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=600'],
            ['title' => 'Buket意思 — Makna Bunga dalam Budaya', 'slug' => 'makna-bunga', 'excerpt' => 'Setiap bunga memiliki makna khusus dalam berbagai budaya. Ketahui sebelum memilih...', 'content' => '<p>Bunga memiliki bahasa universal. Mawar merah melambangkan cinta, bunga lili melambangkan kesucian, dan bunga matahari melambangkan kebahagiaan.</p>', 'date' => '28 Maret 2025', 'category' => 'Panduan', 'image' => 'https://images.unsplash.com/photo-1508610048659-a06b669e3321?w=600'],
            ['title' => 'Ide Buket untuk Setiap Momen Spesial', 'slug' => 'ide-buket-momen', 'excerpt' => 'Dari wisuda hingga anniversary, temukan ide buket yang sempurna...', 'content' => '<p>Setiap momen membutuhkan buket yang berbeda. Wisuda identik dengan bunga-bunga cerah, anniversary dengan mawar romantis, dan ulang tahun dengan rangkaian ceria.</p>', 'date' => '25 Maret 2025', 'category' => 'Inspirasi', 'image' => 'https://images.unsplash.com/photo-1463936575829-25148e1db1b8?w=600'],
        ];

        foreach ($articles as $data) {
            Article::create($data);
        }

        $settings = [
            ['key' => 'store_name', 'value' => '[Nama Toko]'],
            ['key' => 'whatsapp', 'value' => '085649150049'],
            ['key' => 'address', 'value' => 'Jl. [Alamat Toko], Sidoarjo, Jawa Timur'],
            ['key' => 'instagram', 'value' => '@[handle_instagram]'],
            ['key' => 'whatsapp_link', 'value' => '6285649150049'],
            ['key' => 'hours', 'value' => 'Senin–Sabtu: 08.00–20.00 | Minggu: 09.00–17.00'],
        ];

        foreach ($settings as $data) {
            Setting::create($data);
        }
    }
}
