<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Zobrazí všetky príspevky (filtrovanie + triedenie + partial)
    public function index(Request $request)
    {
        $query = Post::query();

        // Filtrovanie podľa autora (napr. user_id)
        if ($request->filled('author')) {
            $query->where('user_id', $request->author);
        }

        // Filtrovanie podľa dátumu (napr. "2025-01-10")
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Triedenie
        if (
            $request->filled('sortBy') &&
            in_array($request->sortBy, ['title','created_at','likes'], true)
        ) {
            $direction = $request->input('direction') === 'asc' ? 'asc' : 'desc';
            $query->orderBy($request->sortBy, $direction);
        }

        // Načítame príspevky (+ user)
        $posts = $query->with('user')->get();

        // Ak je to AJAX, vrátime partial HTML:
        if ($request->ajax()) {
            // Render partial: resources/views/posts/_partials/filtered_posts.blade.php
            $partialHtml = view('posts._partials.filtered_posts', compact('posts'))->render();

            return response($partialHtml, 200)
                ->header('Content-Type', 'text/html');
        }

        // Inak: vrátime bežnú "index" šablónu
        return view('posts.index', compact('posts'));
    }

    // Formulár na vytvorenie nového príspevku
    public function create()
    {
        return view('posts.create');
    }

    // Uloženie nového príspevku
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:51200',
        ]);

        $post = new Post($validated);
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

    // Zobrazenie konkrétneho príspevku
    public function show(Post $post)
    {
        // Laravel automaticky nájde Post podľa ID
        return view('posts.show', compact('post'));
    }

    // Formulár na úpravu príspevku
    public function edit(Post $post)
    {
        // Over, či je user autor
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Nemáte právo upravovať tento príspevok.');
        }

        return view('posts.edit', compact('post'));
    }

    // Aktualizácia príspevku
    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Nemáte právo upravovať tento príspevok.');
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $post->update($validated);

        return redirect()->route('posts.index')->with('success','Príspevok bol úspešne upravený.');
    }

    // Odstránenie príspevku
    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Nemáte právo odstrániť tento príspevok.');
        }

        $post->delete();
        return redirect()->route('posts.index')->with('success','Príspevok bol úspešne odstránený.');
    }

    // Lajkovanie príspevkov (voliteľná funkcia)
    public function like($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['error' => 'Príspevok sa nenašiel'], 404);
        }

        $post->likes++;
        $post->save();

        return response()->json(['likes' => $post->likes]);
    }
}
