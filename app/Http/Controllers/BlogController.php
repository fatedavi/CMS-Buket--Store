<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $articles = Article::latest()->get();

        return view('pages.blog.index', compact('articles'));
    }

    public function show(string $slug): View
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        $related = Article::where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->latest()
            ->limit(3)
            ->get();

        return view('pages.blog.show', compact('article', 'related'));
    }
}
