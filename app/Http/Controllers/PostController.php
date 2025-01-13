<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Zobrazí všetky príspevky (filtrovanie + triedenie + partial)
    public function index(Request $request)
    {
        // Vytvor query
        $query = Post::query();

        // 1. Filtrovanie podľa autora (user_id)
        if ($request->filled('author')) {
            $query->where('user_id', $request->author);
        }

        // 2. Filtrovanie podľa dátumu (napr. 2025-01-01)
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // 3. Triedenie (title, created_at, likes)
        if (
            $request->filled('sortBy') &&
            in_array($request->sortBy, ['title','created_at','likes'], true)
        ) {
            $direction = $request->input('direction') === 'asc' ? 'asc' : 'desc';
            $query->orderBy($request->sortBy, $direction);
        }

        // Načítame príspevky + ich používateľov
        $posts = $query->with('user')->get();

        // Rozlíš AJAX vs. normál (BEZ JSON, posielame partial HTML)
        if ($request->ajax()) {
            // Vyrenderuj partial Blade do reťazca
            $partialHtml = view('posts._partials.filtered_posts', compact('posts'))
                ->render();

            // Vráť ako text/html
            return response($partialHtml, 200)
                ->header('Content-Type', 'text/html');
        }

        // Normálny request => vrátime "index.blade.php" (celú stránku)
        return view('posts.index', compact('posts'));
    }

    // ========= Ostatné CRUD metódy ==========

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:51200',
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

        return redirect()->route('posts.index')
            ->with('success','Príspevok bol úspešne vytvorený.');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        // Overenie, či ide o autora
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Nemáte právo upravovať tento príspevok.');
        }
        return view('posts.edit', compact('post'));
    }

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

        return redirect()->route('posts.index')
            ->with('success', 'Príspevok bol úspešne upravený.');
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Nemáte právo odstrániť tento príspevok.');
        }
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Príspevok bol úspešne odstránený.');
    }

    // Lajkovanie (voliteľné)
    public function like($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        $post->likes++;
        $post->save();

        return response()->json(['likes' => $post->likes]);
    }
}
