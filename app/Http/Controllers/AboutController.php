<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\About;

class AboutController extends Controller
{
    // Zobrazenie obsahu sekcie "O nás" pre všetkých používateľov
    public function index()
    {
        $content = About::all();
        return view('about.index', compact('content'));
    }

    // Zobrazenie formulára na vytvorenie obsahu (len pre adminov)
    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Nemáte oprávnenie na túto akciu.');
        }
        return view('about.create');

    }

    // Uloženie nového obsahu (len pre adminov)
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

    // Zobrazenie formulára na úpravu obsahu (len pre adminov)
    public function edit(About $about)
    {
        $this->authorize('admin');
        return view('about.edit', compact('about'));
    }

    // Aktualizácia existujúceho obsahu (len pre adminov)
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

    // Odstránenie obsahu (len pre adminov)
    public function destroy(About $about)
    {
        $this->authorize('admin');

        $about->delete();

        return redirect()->route('about.index')->with('success', 'Obsah bol úspešne odstránený.');
    }
}
