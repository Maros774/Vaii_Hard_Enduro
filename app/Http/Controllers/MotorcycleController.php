<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Motorcycle;

class MotorcycleController extends Controller
{
    public function index()
    {
        $motorcycles = Motorcycle::all();
        return view('motorcycles.index', compact('motorcycles'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Nemáte oprávnenie na túto akciu.');
        }
        return view('motorcycles.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Nemáte oprávnenie na túto akciu.');
        }

        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'image' => 'nullable|file|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('motorcycles_images', 'public');
            $validated['image'] = $imagePath;
        }

        Motorcycle::create($validated);

        return redirect()->route('motorcycles.index')->with('success', 'Motocykel bol úspešne pridaný.');
    }

    public function edit(Motorcycle $motorcycle)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Nemáte oprávnenie na túto akciu.');
        }
        return view('motorcycles.edit', compact('motorcycle'));
    }

    public function update(Request $request, Motorcycle $motorcycle)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Nemáte oprávnenie na túto akciu.');
        }

        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'image' => 'nullable|file|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($motorcycle->image) {
                \Storage::disk('public')->delete($motorcycle->image);
            }
            $imagePath = $request->file('image')->store('motorcycles_images', 'public');
            $validated['image'] = $imagePath;
        }

        $motorcycle->update($validated);

        return redirect()->route('motorcycles.index')->with('success', 'Motocykel bol úspešne aktualizovaný.');
    }

    public function destroy(Motorcycle $motorcycle)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Nemáte oprávnenie na túto akciu.');
        }

        if ($motorcycle->image) {
            \Storage::disk('public')->delete($motorcycle->image);
        }

        $motorcycle->delete();

        return redirect()->route('motorcycles.index')->with('success', 'Motocykel bol úspešne odstránený.');
    }
}
