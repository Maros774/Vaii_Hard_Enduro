<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Nemáte oprávnenie na túto akciu.');
        }
        return view('about.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Nemáte oprávnenie na túto akciu.');
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:20480', // Max 20MB
        ]);

        if ($request->hasFile('media')) {
            $filePath = $request->file('media')->store('abouts_media', 'public');
            $validated['media'] = $filePath;
        }

        About::create($validated);

        return redirect()->route('about.index')->with('success', 'Obsah bol úspešne vytvorený.');
    }

    public function edit(About $about)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Nemáte oprávnenie na túto akciu.');
        }
        return view('about.edit', compact('about'));
    }

    public function update(Request $request, About $about)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Nemáte oprávnenie na túto akciu.');
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:20480', // Max 20MB
        ]);

        if ($request->hasFile('media')) {
            if ($about->media) {
                Storage::disk('public')->delete($about->media);
            }
            $filePath = $request->file('media')->store('abouts_media', 'public');
            $validated['media'] = $filePath;
        }

        $about->update($validated);

        return redirect()->route('about.index')->with('success', 'Obsah bol úspešne aktualizovaný.');
    }

    public function destroy(About $about)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Nemáte oprávnenie na túto akciu.');
        }

        if ($about->media) {
            Storage::disk('public')->delete($about->media);
        }

        $about->delete();

        return redirect()->route('about.index')->with('success', 'Obsah bol úspešne odstránený.');
    }
}
