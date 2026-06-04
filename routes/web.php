<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [ProductController::class, 'index'])->name('catalog.index');
Route::get('/katalog/{slug}', [ProductController::class, 'show'])->name('catalog.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/kontak', [ContactController::class, 'index'])->name('contact');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send')->middleware('throttle:30,1');
Route::get('/chat/messages', [ChatController::class, 'messages'])->name('chat.messages');
Route::get('/chat/admin-status', [ChatController::class, 'adminStatus'])->name('chat.admin.status');

Route::prefix('/admin')->name('admin.')->middleware(['auth', 'is_admin', 'track.admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/produk', [AdminController::class, 'products'])->name('products');
    Route::get('/produk/baru', [AdminController::class, 'productsCreate'])->name('products.create');
    Route::post('/produk', [AdminController::class, 'productsStore'])->name('products.store');
    Route::get('/produk/{product}/edit', [AdminController::class, 'productsEdit'])->name('products.edit');
    Route::put('/produk/{product}', [AdminController::class, 'productsUpdate'])->name('products.update');
    Route::delete('/produk/{product}', [AdminController::class, 'productsDestroy'])->name('products.destroy');
    Route::get('/artikel', [AdminController::class, 'articles'])->name('articles');
    Route::get('/artikel/baru', [AdminController::class, 'articlesCreate'])->name('articles.create');
    Route::post('/artikel', [AdminController::class, 'articlesStore'])->name('articles.store');
    Route::get('/artikel/{article}/edit', [AdminController::class, 'articlesEdit'])->name('articles.edit');
    Route::put('/artikel/{article}', [AdminController::class, 'articlesUpdate'])->name('articles.update');
    Route::delete('/artikel/{article}', [AdminController::class, 'articlesDestroy'])->name('articles.destroy');
    Route::get('/chat', [AdminController::class, 'chat'])->name('chat');
    Route::get('/chat/arsip', [AdminController::class, 'chatArchive'])->name('chat.archive');
    Route::get('/chat/arsip/{conversation}', [AdminController::class, 'chatArchiveShow'])->name('chat.archive.show');
    Route::get('/chat/{conversation}', [AdminController::class, 'chatShow'])->name('chat.show');
    Route::get('/chat/{conversation}/messages', [AdminController::class, 'chatMessages'])->name('chat.messages.fetch');
    Route::post('/chat/{conversation}/reply', [AdminController::class, 'chatReply'])->name('chat.reply');
    Route::post('/chat/{conversation}/close', [AdminController::class, 'chatClose'])->name('chat.close');
    Route::get('/tips', [AdminController::class, 'tips'])->name('tips');
    Route::get('/tips/baru', [AdminController::class, 'tipsCreate'])->name('tips.create');
    Route::post('/tips', [AdminController::class, 'tipsStore'])->name('tips.store');
    Route::get('/tips/{tip}/edit', [AdminController::class, 'tipsEdit'])->name('tips.edit');
    Route::put('/tips/{tip}', [AdminController::class, 'tipsUpdate'])->name('tips.update');
    Route::delete('/tips/{tip}', [AdminController::class, 'tipsDestroy'])->name('tips.destroy');
    Route::get('/pengaturan', [AdminController::class, 'settings'])->name('settings');
    Route::post('/pengaturan', [AdminController::class, 'settingsUpdate'])->name('settings.update');
    Route::get('/pengguna', [AdminController::class, 'users'])->name('users');
    Route::get('/pengguna/baru', [AdminController::class, 'usersCreate'])->name('users.create');
    Route::post('/pengguna', [AdminController::class, 'usersStore'])->name('users.store');
    Route::get('/pengguna/{user}/edit', [AdminController::class, 'usersEdit'])->name('users.edit');
    Route::put('/pengguna/{user}', [AdminController::class, 'usersUpdate'])->name('users.update');
    Route::delete('/pengguna/{user}', [AdminController::class, 'usersDestroy'])->name('users.destroy');
});
