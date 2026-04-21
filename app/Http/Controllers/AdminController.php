<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_products' => 12,
            'total_articles' => 6,
            'total_categories' => 5,
            'monthly_orders' => 45,
        ];

        $recentProducts = [
            ['name'=>'Pink Romance','category'=>'Wisuda','status'=>'Aktif','image'=>'https://images.unsplash.com/photo-1487530811176-3780de880c2d?w=100'],
            ['name'=>'Cream Elegance','category'=>'Anniversary','status'=>'Aktif','image'=>'https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=100'],
            ['name'=>'Garden Fresh','category'=>'Ulang Tahun','status'=>'Aktif','image'=>'https://images.unsplash.com/photo-1490750967868-88df5691cc45?w=100'],
            ['name'=>'Terracotta Bloom','category'=>'Wisuda','status'=>'Draft','image'=>'https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=100'],
            ['name'=>'Lavender Dream','category'=>'Anniversary','status'=>'Aktif','image'=>'https://images.unsplash.com/photo-1508610048659-a06b669e3321?w=100'],
        ];

        return view('admin.dashboard', compact('stats', 'recentProducts'));
    }

    public function products(): View
    {
        $products = [
            ['id'=>1,'name'=>'Pink Romance','slug'=>'pink-romance','category'=>'Wisuda','status'=>'Aktif','date'=>'15 April 2025','image'=>'https://images.unsplash.com/photo-1487530811176-3780de880c2d?w=100'],
            ['id'=>2,'name'=>'Cream Elegance','slug'=>'cream-elegance','category'=>'Anniversary','status'=>'Aktif','date'=>'14 April 2025','image'=>'https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=100'],
            ['id'=>3,'name'=>'Garden Fresh','slug'=>'garden-fresh','category'=>'Ulang Tahun','status'=>'Aktif','date'=>'13 April 2025','image'=>'https://images.unsplash.com/photo-1490750967868-88df5691cc45?w=100'],
            ['id'=>4,'name'=>'Terracotta Bloom','slug'=>'terracotta-bloom','category'=>'Wisuda','status'=>'Draft','date'=>'12 April 2025','image'=>'https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=100'],
            ['id'=>5,'name'=>'Lavender Dream','slug'=>'lavender-dream','category'=>'Anniversary','status'=>'Aktif','date'=>'11 April 2025','image'=>'https://images.unsplash.com/photo-1508610048659-a06b669e3321?w=100'],
            ['id'=>6,'name'=>'Sunny Daisy','slug'=>'sunny-daisy','category'=>'Ulang Tahun','status'=>'Aktif','date'=>'10 April 2025','image'=>'https://images.unsplash.com/photo-1463936575829-25148e1db1b8?w=100'],
            ['id'=>7,'name'=>'White Purity','slug'=>'white-purity','category'=>'Wedding','status'=>'Aktif','date'=>'9 April 2025','image'=>'https://images.unsplash.com/photo-1593698054589-22f60f85bced?w=100'],
            ['id'=>8,'name'=>'Forest Sage','slug'=>'forest-sage','category'=>'Wisuda','status'=>'Aktif','date'=>'8 April 2025','image'=>'https://images.unsplash.com/photo-1420593248178-d88870618ca0?w=100'],
        ];

        return view('admin.products.index', compact('products'));
    }

    public function productsCreate(): View
    {
        return view('admin.products.create');
    }

    public function articles(): View
    {
        return view('admin.articles.index');
    }

    public function settings(): View
    {
        return view('admin.settings');
    }
}
