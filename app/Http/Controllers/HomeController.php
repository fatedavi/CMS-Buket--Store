<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tip;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Ambil produk featured dari DB (Aktif, prioritaskan yang ada badge)
        $featuredProducts = Product::where('status', 'Aktif')
            ->orderByRaw("CASE WHEN badge IS NOT NULL AND badge != '' THEN 0 ELSE 1 END")
            ->latest()
            ->take(9)
            ->get();

        $categories = [
            ['name' => 'Wisuda',      'slug' => 'wisuda',      'count' => Product::where('status','Aktif')->where('category','Wisuda')->count(),      'image' => 'https://images.unsplash.com/photo-1487530811176-3780de880c2d?w=400'],
            ['name' => 'Anniversary', 'slug' => 'anniversary', 'count' => Product::where('status','Aktif')->where('category','Anniversary')->count(), 'image' => 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=400'],
            ['name' => 'Ulang Tahun', 'slug' => 'ulang-tahun', 'count' => Product::where('status','Aktif')->where('category','Ulang Tahun')->count(), 'image' => 'https://images.unsplash.com/photo-1490750967868-88df5691cc45?w=400'],
            ['name' => 'Wedding',     'slug' => 'wedding',     'count' => Product::where('status','Aktif')->where('category','Wedding')->count(),     'image' => 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=400'],
            ['name' => 'Custom',      'slug' => 'custom',      'count' => Product::where('status','Aktif')->where('category','Custom')->count(),      'image' => 'https://images.unsplash.com/photo-1508610048659-a06b669e3321?w=400'],
        ];

        $tips = Tip::where('is_active', true)->orderBy('order')->get();

        return view('pages.home', compact('featuredProducts', 'categories', 'tips'));
    }
}
