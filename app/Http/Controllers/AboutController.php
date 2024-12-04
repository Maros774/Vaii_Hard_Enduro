<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class AboutController extends Controller
{
    public function index()
    {
        // Načítanie obrázkov a videí z priečinkov
        $images = File::files(public_path('media/about/images'));
        $videos = File::files(public_path('media/about/videos'));

        // Posielame cesty k súborom do pohľadu
        return view('about', compact('images', 'videos'));
    }
}
