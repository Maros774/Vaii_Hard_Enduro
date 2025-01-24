<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Zoznam príspevkov
    public function index(Request $request)
    {
        $query = Post::query();

        // Filtrovanie podľa autora (user_id)
        if ($request->filled('author')) {
            $query->where('user_id', $request->author);
        }

        // Filtrovanie podľa dátumu
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Filtrovanie (vyhľadávanie) podľa title alebo content
        if ($request->filled('searchTerm')) {
            $term = $request->input('searchTerm');
            $query->where(function($q) use ($term) {
                $q->where('title', 'LIKE', "%{$term}%")
                    ->orWhere('content', 'LIKE', "%{$term}%");
            });
        }

        // Môžeš pridať triedenie (napr. ->orderBy('created_at','desc'))

        $posts = $query->with('user')->get();

        // Ak je to AJAX (napr. fetch s X-Requested-With: XMLHttpRequest),
        // vrátime partial HTML, aby sa nevnoril layout do seba
        if ($request->ajax()) {
            $partialHtml = view('posts._partials.filtered_posts', compact('posts'))->render();
            return response($partialHtml, 200)
                ->header('Content-Type', 'text/html');
        }

        // Inak normálne vrátime "index" šablónu
        return view('posts.index', compact('posts'));
    }

    // Detaily príspevku
    public function show(Post $post)
    {
        // $post->load('comments.user'); // ak chceš komentáre s menom usera
        return view('posts.show', compact('post'));
    }

    // Formulár na vytvorenie
    public function create()
    {
        return view('posts.create');
    }

    // Uloženie nového
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|max:255',
            'content' => 'required',
            'image'   => 'nullable|image|max:2048',
            'video'   => 'nullable|mimes:mp4,mov,avi,wmv|max:51200'
        ]);

        // Vytvor príspevok
        $post = new Post();
        $post->title   = $validated['title'];
        $post->content = $validated['content'];
        // user_id (napr. prihlásený):
        $post->user_id = auth()->id() ?? null;

        // Obrázok
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $post->image_path = $path;
        }

        // Video
        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('videos', 'public');
            $post->video_path = $path;
        }

        $post->save();

        return redirect()->route('posts.index')
            ->with('success','Príspevok bol úspešne vytvorený.');
    }

    // Formulár na úpravu
    public function edit(Post $post)
    {
        // Ak chceš overiť autora:
        // if ($post->user_id !== auth()->id()) {
        //     abort(403, 'Nemáte právo upravovať tento príspevok.');
        // }
        return view('posts.edit', compact('post'));
    }

    // Aktualizácia
    public function update(Request $request, Post $post)
    {
        // Overenie autora, ak treba:
        // if ($post->user_id !== auth()->id()) {
        //     abort(403, 'Nemáte právo upravovať tento príspevok.');
        // }

        $validated = $request->validate([
            'title'   => 'required|max:255',
            'content' => 'required',
            'image'   => 'nullable|image|max:2048',
            'video'   => 'nullable|mimes:mp4,mov,avi,wmv|max:51200'
        ]);

        $post->title   = $validated['title'];
        $post->content = $validated['content'];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $post->image_path = $path;
        }

        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('videos', 'public');
            $post->video_path = $path;
        }

        $post->save();

        return redirect()->route('posts.index')
            ->with('success','Príspevok bol úspešne upravený.');
    }

    // Odstránenie
    public function destroy(Post $post)
    {
        // if ($post->user_id !== auth()->id()) {
        //     abort(403, 'Nemáte právo odstrániť tento príspevok.');
        // }

        $post->delete();

        return redirect()->route('posts.index')
            ->with('success','Príspevok bol vymazaný.');
    }

    // Like
    public function like(Post $post)
    {
        $post->likes++;
        $post->save();

        return response()->json(['likes' => $post->likes]);
    }
}
