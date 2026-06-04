<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::where('status', 'Aktif')->latest()->get();

        $categories = array_merge(
            ['Semua'],
            $products->pluck('category')->unique()->sort()->values()->toArray()
        );

        return view('pages.catalog.index', compact('products', 'categories'));
    }

    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)->where('status', 'Aktif')->firstOrFail();

        $relatedProducts = Product::where('status', 'Aktif')
            ->where('id', '!=', $product->id)
            ->where('category', $product->category)
            ->latest()
            ->take(4)
            ->get();

        // Kalau kurang dari 4, tambah dari produk lain
        if ($relatedProducts->count() < 4) {
            $extra = Product::where('status', 'Aktif')
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $relatedProducts->pluck('id'))
                ->latest()
                ->take(4 - $relatedProducts->count())
                ->get();
            $relatedProducts = $relatedProducts->concat($extra);
        }

        return view('pages.catalog.show', compact('product', 'relatedProducts'));
    }

    // Helper static untuk generate URL gambar produk (dipakai di view)
    public static function imageUrl(?string $image): string
    {
        if (! $image) {
            return '';
        }
        if (str_starts_with($image, 'http')) {
            return $image;
        }

        return Storage::url($image);
    }
}
