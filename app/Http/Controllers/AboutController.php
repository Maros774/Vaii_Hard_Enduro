<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\About;

class AboutController extends Controller
{
    public function index()
    {
        $content = About::all();
        return view('about.index', compact('content'));
    }

    public function create()
    {
        $this->authorize('admin');
        return view('about.create');
    }

    public function store(Request $request)
    {
        $this->authorize('admin');
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);
        About::create($validated);
        return redirect()->route('about.index')->with('success', 'Obsah bol úspešne vytvorený.');
    }

    public function edit(About $about)
    {
        $this->authorize('admin');
        return view('about.edit', compact('about'));
    }

    public function update(Request $request, About $about)
    {
        $this->authorize('admin');
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);
        $about->update($validated);
        return redirect()->route('about.index')->with('success', 'Obsah bol úspešne aktualizovaný.');
    }

    public function destroy(About $about)
    {
        $this->authorize('admin');
        $about->delete();
        return redirect()->route('about.index')->with('success', 'Obsah bol úspešne odstránený.');
    }
}
