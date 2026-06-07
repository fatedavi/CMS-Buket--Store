<?php

namespace App\Http\Controllers;

use App\Events\ChatClosed;
use App\Events\MessageSent;
use App\Models\Article;
use App\Models\Category;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Tip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_products' => Product::count(),
            'total_articles' => Article::count(),
            'total_categories' => Category::count(),
            'monthly_chats' => ChatConversation::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        $recentProducts = Product::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentProducts'));
    }

    public function products(): View
    {
        $products = Product::latest()->get();

        return view('admin.products.index', compact('products'));
    }

    public function productsCreate(): View
    {
        return view('admin.products.create');
    }

    public function productsStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'badge' => 'nullable|string|max:255',
            'status' => 'required|in:Aktif,Draft',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($data['image']);
        }

        Product::create($data);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function productsEdit(Product $product): View
    {
        return view('admin.products.edit', compact('product'));
    }

    public function productsUpdate(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,'.$product->id,
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'badge' => 'nullable|string|max:255',
            'status' => 'required|in:Aktif,Draft',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        // Hapus gambar lama kalau centang remove
        if ($request->boolean('remove_image') && $product->image) {
            if (! str_starts_with($product->image, 'http')) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = null;
        }

        // Upload gambar baru (menggantikan lama jika ada)
        if ($request->hasFile('image')) {
            // Hapus gambar lama sebelum upload baru
            if ($product->image && ! str_starts_with($product->image, 'http')) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        } else {
            // Tidak ada upload baru — jangan ubah image
            unset($data['image']);
        }

        $product->update($data);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil diperbarui.');
    }

    public function productsDestroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus.');
    }

    public function articles(): View
    {
        $articles = Article::latest()->get();

        return view('admin.articles.index', compact('articles'));
    }

    public function articlesCreate(): View
    {
        return view('admin.articles.create');
    }

    public function articlesStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'category' => 'required|string|max:255',
            'image' => 'nullable|string|max:500',
            'date' => 'nullable|string|max:255',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['date'] = $data['date'] ?: now()->format('j F Y');

        Article::create($data);

        return redirect()->route('admin.articles')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function articlesEdit(Article $article): View
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function articlesUpdate(Request $request, Article $article): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug,'.$article->id,
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'category' => 'required|string|max:255',
            'image' => 'nullable|string|max:500',
            'date' => 'nullable|string|max:255',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        $article->update($data);

        return redirect()->route('admin.articles')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function articlesDestroy(Article $article): RedirectResponse
    {
        $article->delete();

        return redirect()->route('admin.articles')->with('success', 'Artikel berhasil dihapus.');
    }

    public function chat(): View
    {
        $conversations = ChatConversation::withCount('messages')
            ->where('status', 'active')
            ->latest()
            ->paginate(20);

        return view('admin.chat.index', compact('conversations'));
    }

    public function chatShow(ChatConversation $conversation): View
    {
        $conversation->load('messages');

        return view('admin.chat.show', compact('conversation'));
    }

    public function chatMessages(ChatConversation $conversation): JsonResponse
    {
        $conversation->load('messages');

        return response()->json([
            'messages' => $conversation->messages,
            'status' => $conversation->status,
        ]);
    }

    public function chatReply(Request $request, ChatConversation $conversation): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $msg = ChatMessage::create([
            'chat_conversation_id' => $conversation->id,
            'sender' => 'admin',
            'message' => $data['message'],
        ]);

        try {
            broadcast(new MessageSent($msg, $conversation));
        } catch (BroadcastException $e) {
            // Reverb tidak jalan — chat tetap berfungsi
        }

        // Kalau dipanggil via AJAX (Accept: application/json), return JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
            ]);
        }

        return redirect()->route('admin.chat.show', $conversation)
            ->with('success', 'Pesan terkirim.');
    }

    public function chatArchive(): View
    {
        $conversations = ChatConversation::withCount('messages')
            ->where('status', 'closed')
            ->latest()
            ->paginate(20);

        return view('admin.chat.archive', compact('conversations'));
    }

    public function chatArchiveShow(ChatConversation $conversation): View
    {
        abort_if($conversation->status !== 'closed', 404);

        $conversation->load('messages');

        return view('admin.chat.archive-show', compact('conversation'));
    }

    public function chatClose(ChatConversation $conversation): RedirectResponse
    {
        $conversation->update(['status' => 'closed']);

        try {
            broadcast(new ChatClosed($conversation));
        } catch (BroadcastException $e) {
            // Reverb tidak jalan — chat tetap berfungsi
        }

        return redirect()->route('admin.chat')->with('success', 'Percakapan ditutup.');
    }

    public function tips(): View
    {
        $tips = Tip::orderBy('order')->get();

        return view('admin.tips.index', compact('tips'));
    }

    public function tipsCreate(): View
    {
        return view('admin.tips.create');
    }

    public function tipsStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'icon' => 'nullable|string|max:50|in:'.implode(',', Tip::iconOptions()),
            'background_image' => 'nullable|image|max:2048',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? 0;

        if ($request->hasFile('background_image')) {
            $data['background_image'] = $request->file('background_image')->store('tips', 'public');
        } else {
            unset($data['background_image']);
        }

        Tip::create($data);

        return redirect()->route('admin.tips')->with('success', 'Tips berhasil ditambahkan.');
    }

    public function tipsEdit(Tip $tip): View
    {
        return view('admin.tips.edit', compact('tip'));
    }

    public function tipsUpdate(Request $request, Tip $tip): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'icon' => 'nullable|string|max:50|in:'.implode(',', Tip::iconOptions()),
            'background_image' => 'nullable|image|max:2048',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? 0;

        if ($request->boolean('remove_background_image') && $tip->background_image) {
            if (! str_starts_with($tip->background_image, 'http')) {
                Storage::disk('public')->delete($tip->background_image);
            }
            $data['background_image'] = null;
        }

        if ($request->hasFile('background_image')) {
            if ($tip->background_image && ! str_starts_with($tip->background_image, 'http')) {
                Storage::disk('public')->delete($tip->background_image);
            }
            $data['background_image'] = $request->file('background_image')->store('tips', 'public');
        } else {
            unset($data['background_image']);
        }

        $tip->update($data);

        return redirect()->route('admin.tips')->with('success', 'Tips berhasil diperbarui.');
    }

    public function tipsDestroy(Tip $tip): RedirectResponse
    {
        if ($tip->background_image && ! str_starts_with($tip->background_image, 'http')) {
            Storage::disk('public')->delete($tip->background_image);
        }
        $tip->delete();

        return redirect()->route('admin.tips')->with('success', 'Tips berhasil dihapus.');
    }

    public function categories(): View
    {
        $categories = Category::latest()->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function categoriesCreate(): View
    {
        return view('admin.categories.create');
    }

    public function categoriesStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        } else {
            unset($data['image']);
        }

        Category::create($data);

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function categoriesEdit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function categoriesUpdate(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,'.$category->id,
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->boolean('remove_image') && $category->image) {
            if (! str_starts_with($category->image, 'http')) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($category->image && ! str_starts_with($category->image, 'http')) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        } else {
            unset($data['image']);
        }

        $category->update($data);

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function categoriesDestroy(Category $category): RedirectResponse
    {
        if ($category->image && ! str_starts_with($category->image, 'http')) {
            Storage::disk('public')->delete($category->image);
        }
        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil dihapus.');
    }

    public function settings(): View
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('admin.settings', compact('settings'));
    }

    public function settingsUpdate(Request $request): RedirectResponse
    {
        $textKeys = ['store_name', 'whatsapp', 'address', 'instagram', 'hours'];

        $rules = [];
        foreach ($textKeys as $key) {
            $rules[$key] = $key === 'store_name' || $key === 'whatsapp' ? 'required|string|max:255' : 'nullable|string|max:255';
        }

        $rules['hero_slide_0'] = 'nullable|image|max:2048';
        $rules['hero_slide_1'] = 'nullable|image|max:2048';
        $rules['hero_slide_2'] = 'nullable|image|max:2048';
        $rules['hero_slide_3'] = 'nullable|image|max:2048';
        $rules['hero_slide_4'] = 'nullable|image|max:2048';

        $request->validate($rules);

        foreach ($textKeys as $key) {
            if ($request->filled($key)) {
                Setting::updateOrCreate(['key' => $key], ['value' => $request->input($key)]);
                Cache::forget('setting.'.$key);
            }
        }

        $imageKeys = ['hero_slide_0', 'hero_slide_1', 'hero_slide_2', 'hero_slide_3', 'hero_slide_4'];

        foreach ($imageKeys as $key) {
            if ($request->hasFile($key)) {
                $path = $request->file($key)->store('settings', 'public');
                Setting::updateOrCreate(['key' => $key], ['value' => $path]);
                Cache::forget('setting.'.$key);
            }
        }

        return redirect()->route('admin.settings')->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function users(): View
    {
        $users = User::latest()->get();

        return view('admin.users.index', compact('users'));
    }

    public function usersCreate(): View
    {
        return view('admin.users.create');
    }

    public function usersStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'is_admin' => 'boolean',
        ]);

        $data['is_admin'] = $request->boolean('is_admin');
        User::create($data);

        return redirect()->route('admin.users')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function usersEdit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function usersUpdate(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6',
            'is_admin' => 'boolean',
        ]);

        $data['is_admin'] = $request->boolean('is_admin');
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function usersDestroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Pengguna berhasil dihapus.');
    }
}
