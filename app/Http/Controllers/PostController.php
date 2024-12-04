<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $post = new Post($validatedData);
        $post->user_id = auth()->id(); // Priradenie aktuálneho používateľa
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Príspevok bol úspešne vytvorený.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // Zabezpečenie - iba autor príspevku ho môže upraviť
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Nemáte právo upraviť tento príspevok.');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Zabezpečenie - iba autor príspevku ho môže aktualizovať
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
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Zabezpečenie - iba autor príspevku ho môže vymazať
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Nemáte právo vymazať tento príspevok.');
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Príspevok bol úspešne vymazaný.');
    }

    /**
     * Search for posts based on a query.
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        $posts = Post::where('title', 'LIKE', "%{$query}%")->get();
        return response()->json($posts);
    }
}
