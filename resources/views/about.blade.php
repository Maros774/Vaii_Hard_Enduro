@extends('layouts.app')

@section('content')
    <h1>O nás</h1>
    <p>Sme nadšenci hard endura, ktorí sa venujú tomuto športu už niekoľko rokov. Jazdíme po rôznych tratiach a prekážkach, pričom sa zúčastňujeme aj rôznych súťaží.</p>

    <h2>Naše obrázky</h2>
    <div class="photo-gallery">
        @foreach ($images as $image)
            <div>
                <figure>
                    <img src="{{ asset('media/about/images/' . basename($image)) }}" alt="O nás obrázok" class="img-fluid img-gallery">
                    <figcaption>{{ basename($image) }}</figcaption>
                </figure>
            </div>
        @endforeach
    </div>

    <h2>Naše videá</h2>
    <div class="video-gallery">
        @foreach ($videos as $video)
            <div class="video-item">
                <video controls class="w-100">
                    <source src="{{ asset('media/about/videos/' . basename($video)) }}" type="video/mp4">
                    Váš prehliadač nepodporuje prehrávanie videí.
                </video>
            </div>
        @endforeach
    </div>
@endsection
