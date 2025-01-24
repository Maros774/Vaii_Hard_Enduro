@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $post->title }}</h1>
        <p>{{ $post->content }}</p>

        {{-- Obrázok --}}
        @if ($post->image_path)
            <img
                src="{{ asset('storage/' . $post->image_path) }}"
                alt="Obrázok príspevku"
                class="img-fluid mt-3 custom-media"
            />
        @endif

        {{-- Video --}}
        @if ($post->video_path)
            <video
                controls
                class="mt-3 custom-media"
            >
                <source src="{{ asset('storage/' . $post->video_path) }}" type="video/mp4">
            </video>
        @endif

        {{-- Tlačidlo späť --}}
        <a href="{{ route('posts.index') }}" class="btn btn-secondary mt-3">
            Späť na zoznam
        </a>

        {{-- Komentáre, ak ich používaš --}}
        <hr>
        <h3>Komentáre</h3>
        @forelse($post->comments as $comment)
            <div class="mb-3">
                <strong>{{ $comment->user->name }}</strong>
                <p>{{ $comment->content }}</p>
                @if(auth()->check() && (auth()->id() === $comment->user_id || auth()->user()->role === 'admin'))
                    <form
                        action="{{ route('comments.destroy', $comment->id) }}"
                        method="POST"
                        style="display:inline;"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            Zmazať
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <p>Žiadne komentáre.</p>
        @endforelse

        @auth
            <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mt-3">
                @csrf
                <textarea name="content" rows="3" class="form-control" required></textarea>
                <button type="submit" class="btn btn-primary mt-2">Pridať komentár</button>
            </form>
        @endauth
    </div>
@endsection
