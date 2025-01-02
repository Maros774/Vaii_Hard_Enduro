<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Zobrazí všetky príspevky.
     */
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    /**
     * Zobrazí formulár na vytvorenie nového príspevku.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Uloží nový príspevok do databázy.
     */
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

    /**
     * Zobrazí konkrétny príspevok.
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Zobrazí formulár na úpravu príspevku.
     */
    public function edit(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Nemáte právo upraviť tento príspevok.');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Aktualizuje príspevok v databáze.
     */
    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Nemáte právo na editáciu tohto príspevku.');
        }

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $post->update($validatedData);

        return redirect()->route('posts.index')->with('success', 'Príspevok bol úspešne upravený.');
    }

    /**
     * Odstráni príspevok z databázy.
     */
    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Nemáte právo vymazať tento príspevok.');
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Príspevok bol úspešne vymazaný.');
    }

    /**
     * Vyhľadáva príspevky podľa kľúčového slova.
     */
    public function search(Request $request)
    {
        $query = $request->get('query'); // Načítanie vyhľadávacieho dotazu
        if (!$query) {
            return response()->json([]); // Prázdne výsledky, ak nie je zadaný text
        }

        $posts = Post::where('title', 'LIKE', "%{$query}%")->get(); // Vyhľadávanie v názvoch príspevkov

        return response()->json($posts); // Vrátenie výsledkov vo formáte JSON
    }

}
