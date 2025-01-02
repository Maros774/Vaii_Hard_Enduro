<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Zobrazí všetky príspevky
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    // Zobrazí formulár na vytvorenie nového príspevku
    public function create()
    {
        return view('posts.create');
    }

    // Uloží nový príspevok
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $post = new Post($validatedData);
        $post->user_id = auth()->id();
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Príspevok bol úspešne vytvorený.');
    }

    // Zobrazí konkrétny príspevok
    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            abort(404, 'Príspevok neexistuje.');
        }

        return view('posts.show', compact('post'));
    }

    // Zobrazí formulár na úpravu príspevku
    public function edit(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Nemáte právo upravovať tento príspevok.');
        }

        return view('posts.edit', compact('post'));
    }

    // Aktualizuje príspevok
    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Nemáte právo upravovať tento príspevok.');
        }

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $post->update($validatedData);

        return redirect()->route('posts.index')->with('success', 'Príspevok bol úspešne upravený.');
    }

    // Odstráni príspevok
    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Nemáte právo odstrániť tento príspevok.');
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Príspevok bol úspešne odstránený.');
    }

    // Vyhľadávanie príspevkov
    public function search(Request $request)
    {
        $query = $request->query('query', '');
        $posts = Post::where('title', 'LIKE', "%{$query}%")->get(['id', 'title']);
        return response()->json(['data' => $posts]);
    }




}
