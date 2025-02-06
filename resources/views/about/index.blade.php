@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>O nás</h2>

        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('about.create') }}" class="btn btn-success mb-3">Pridať nový obsah</a>
            @endif
        @endauth

        @foreach($content as $about)
            <div class="card mb-3">
                <div class="card-body">
                    <h5>{{ $about->title }}</h5>
                    <p>{{ $about->content }}</p>

                    @if($about->media)
                        @if(Str::endsWith($about->media, ['jpg','jpeg','png']))
                            <!-- Obrázok s "media-rounded" -->
                            <img
                                src="{{ asset('storage/' . $about->media) }}"
                                alt="Media"
                                class="img-fluid mb-3 media-rounded"
                            >
                        @elseif(Str::endsWith($about->media, ['mp4']))
                            <!-- Video s "media-rounded" -->
                            <video
                                controls
                                class="w-100 mb-3 media-rounded custom-media"
                            >
                                <source src="{{ asset('storage/' . $about->media) }}" type="video/mp4">
                                Váš prehliadač nepodporuje prehrávanie videí.
                            </video>
                        @endif
                    @endif

                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('about.edit', $about->id) }}" class="btn btn-warning btn-sm">Upraviť</a>
                            <form action="{{ route('about.destroy', $about->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Vymazať</button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        @endforeach
    </div>
@endsection
