@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Upraviť príspevok</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('posts.update', $post->id) }}"
              method="POST"
              enctype="multipart/form-data"
              id="editForm"
        >
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Názov</label>
                <input
                    type="text"
                    class="form-control"
                    id="title"
                    name="title"
                    value="{{ old('title', $post->title) }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Obsah</label>
                <textarea
                    class="form-control"
                    id="content"
                    name="content"
                    rows="5"
                    required
                >{{ old('content', $post->content) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Nový obrázok (nepovinné)</label>
                <input
                    type="file"
                    name="image"
                    id="image"
                    class="form-control"
                >
                @if($post->image_path)
                    <p>Aktuálny obrázok:
                        <img src="{{ asset('storage/' . $post->image_path) }}"
                             style="max-width:100px;"
                        >
                    </p>
                @endif
            </div>

            <div class="mb-3">
                <label for="video" class="form-label">Nové video (nepovinné)</label>
                <input
                    type="file"
                    name="video"
                    id="video"
                    class="form-control"
                >
                @if($post->video_path)
                    <p>Aktuálne video:
                        <video src="{{ asset('storage/' . $post->video_path) }}"
                               style="max-width:150px;"
                               controls
                        ></video>
                    </p>
                @endif
            </div>

            <button type="submit" class="btn btn-success">Uložiť</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.querySelector('#editForm').addEventListener('submit', function(e) {
            const title   = document.querySelector('#title');
            const content = document.querySelector('#content');

            if (!title.value.trim() || !content.value.trim()) {
                e.preventDefault();
                alert('Všetky polia sú povinné!');
            }
        });
    </script>
@endsection
