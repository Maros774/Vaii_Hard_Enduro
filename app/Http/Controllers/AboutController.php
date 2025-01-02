<?php
//
//namespace App\Http\Controllers;
//
//use Illuminate\Support\Facades\File;
//
//class AboutController extends Controller
//{
//    public function index()
//    {
//        // Načítanie obrázkov a videí z priečinkov
//        $images = File::files(public_path('media/about/images'));
//        $videos = File::files(public_path('media/about/videos'));
//
//        // Posielame cesty k súborom do pohľadu
//        return view('about', compact('images', 'videos'));
//    }
//}


namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class AboutController extends Controller
{
    public function index()
    {
        // Načítanie obrázkov a videí z priečinkov
        $imagePath = public_path('media/about/images');
        $videoPath = public_path('media/about/videos');

        // Kontrola existencie priečinkov a načítanie súborov
        $images = File::exists($imagePath) ? File::files($imagePath) : [];
        $videos = File::exists($videoPath) ? File::files($videoPath) : [];

        // Transformácia súborov na verejné cesty
        $images = array_map(fn($file) => asset('media/about/images/' . $file->getFilename()), $images);
        $videos = array_map(fn($file) => asset('media/about/videos/' . $file->getFilename()), $videos);

        // Posielame cesty k súborom do pohľadu
        return view('about', compact('images', 'videos'));
    }
}

