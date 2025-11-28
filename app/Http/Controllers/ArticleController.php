<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Jika Admin, tampilkan SEMUA artikel
        if ($user->role === 'admin') {
            $articles = Article::latest()->get();
        } 
        else {
            $articles = Article::where('user_id', $user->id)->latest()->get();
        }

        return view('admin.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $articleData = $request->only('title', 'content', 'image');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
            $articleData['image'] = $imagePath;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->articles()->create($articleData);

        session()->flash('success', 'Artikel berhasil dibuat!');
        return redirect()->route('admin.index');
    }

    public function edit(Article $article)
    {
        if (Auth::user()->role !== 'admin' && $article->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit artikel ini.');
        }

        return view('admin.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        if (Auth::user()->role !== 'admin' && $article->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $articleData = $request->only('title', 'content', 'image');

        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::delete('public/' . $article->image);
            }
            $imagePath = $request->file('image')->store('articles', 'public');
            $articleData['image'] = $imagePath;
        }

        $article->update($articleData);

        session()->flash('success', 'Artikel berhasil diperbarui!');
        return redirect()->route('admin.index');
    }

    public function destroy(Article $article)
    {
        if (Auth::user()->role !== 'admin' && $article->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        if ($article->image) {
            Storage::delete('public/' . $article->image);
        }

        $article->delete();

        session()->flash('success', 'Artikel berhasil dihapus!');
        return redirect()->route('admin.index');
    }

    public function show($id)
    {
        $article = Article::with('comments.user')->findOrFail($id);
        return view('articles.show', compact('article'));
    }
}