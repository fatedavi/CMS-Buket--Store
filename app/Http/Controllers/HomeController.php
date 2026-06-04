<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tip;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProducts = Product::where('status', 'Aktif')
            ->orderByRaw("CASE WHEN badge IS NOT NULL AND badge != '' THEN 0 ELSE 1 END")
            ->latest()
            ->take(9)
            ->get();

        $dbCategories = Product::where('status', 'Aktif')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values()
            ->toArray();

        $fallbackImages = [
            'Wisuda' => 'https://images.unsplash.com/photo-1487530811176-3780de880c2d?w=400',
            'Anniversary' => 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=400',
            'Ulang Tahun' => 'https://images.unsplash.com/photo-1490750967868-88df5691cc45?w=400',
            'Wedding' => 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=400',
            'Custom' => 'https://images.unsplash.com/photo-1508610048659-a06b669e3321?w=400',
        ];

        $categories = [];

        foreach ($dbCategories as $name) {
            $key = str_replace(' ', '_', strtolower($name));
            $slug = strtolower(str_replace(' ', '-', $name));
            $saved = setting('category_image_'.$key);
            $image = $saved ? Storage::url($saved) : ($fallbackImages[$name] ?? '');

            $categories[] = [
                'name' => $name,
                'slug' => $slug,
                'count' => Product::where('status', 'Aktif')->where('category', $name)->count(),
                'image' => $image,
            ];
        }

        $heroSlides = [];
        for ($i = 0; $i < 5; $i++) {
            $path = setting('hero_slide_'.$i);
            if ($path) {
                $heroSlides[] = Storage::url($path);
            }
        }
        if (empty($heroSlides)) {
            $heroSlides[] = '/images/hero-bg.png';
        }

        $tips = Tip::where('is_active', true)->orderBy('order')->get();

        return view('pages.home', compact('featuredProducts', 'categories', 'tips', 'heroSlides'));
    }
}
