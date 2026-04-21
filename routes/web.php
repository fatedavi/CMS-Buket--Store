<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [ProductController::class, 'index'])->name('catalog.index');
Route::get('/katalog/{slug}', [ProductController::class, 'show'])->name('catalog.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/kontak', [ContactController::class, 'index'])->name('contact');

Route::prefix('/admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/produk', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/produk/baru', [AdminController::class, 'productsCreate'])->name('admin.products.create');
    Route::get('/artikel', [AdminController::class, 'articles'])->name('admin.articles');
    Route::get('/pengaturan', [AdminController::class, 'settings'])->name('admin.settings');
});
