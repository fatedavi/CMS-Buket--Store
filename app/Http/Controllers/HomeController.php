<?php

namespace App\Http\Controllers;

use App\Models\Category;
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

        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->get()
            ->map(fn ($cat) => [
                'name' => $cat->name,
                'slug' => $cat->slug,
                'count' => $cat->products_count,
                'image' => $cat->image_url,
            ]);

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
