<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::where('status', 'Aktif')->with('category');

        if ($request->filled('kategori')) {
            $category = Category::where('slug', $request->kategori)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        $products = $query->latest()->get();

        $categories = array_merge(
            ['Semua'],
            Category::where('is_active', true)->pluck('name')->toArray()
        );

        $minPrice = Product::where('status', 'Aktif')->min('price');
        $maxPrice = Product::where('status', 'Aktif')->max('price');

        return view('pages.catalog.index', compact('products', 'categories', 'minPrice', 'maxPrice'));
    }

    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)->where('status', 'Aktif')->with('category')->firstOrFail();

        $relatedProducts = Product::where('status', 'Aktif')
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->latest()
            ->take(4)
            ->get();

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
