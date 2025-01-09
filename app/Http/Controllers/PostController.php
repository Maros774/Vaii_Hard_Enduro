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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:51200', // 50MB max
        ]);

        $post = new Post($validatedData);
        $post->user_id = auth()->id();

        // Uloženie fotky
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $post->image_path = $imagePath;
        }

        // Uloženie videa
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('videos', 'public');
            $post->video_path = $videoPath;
        }

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
        $query = $request->input('query');
        $posts = Post::where('title', 'like', "%$query%")
            ->orWhere('content', 'like', "%$query%")
            ->get();

        return response()->json($posts);
    }


    public function like($id)
    {
        $post = Post::findOrFail($id);
        $post->likes += 1; // Pridanie lajku
        $post->save();

        return response()->json(['likes' => $post->likes]);
    }



}
