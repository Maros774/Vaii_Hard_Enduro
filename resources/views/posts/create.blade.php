@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Vytvoriť príspevok</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('posts.store') }}"
              method="POST"
              enctype="multipart/form-data"
              id="createForm"
        >
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">Názov</label>
                <input
                    type="text"
                    class="form-control"
                    id="title"
                    name="title"
                    value="{{ old('title') }}"
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
                >{{ old('content') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Obrázok (nepovinné)</label>
                <input
                    type="file"
                    name="image"
                    id="image"
                    class="form-control"
                >
            </div>

            <div class="mb-3">
                <label for="video" class="form-label">Video (nepovinné)</label>
                <input
                    type="file"
                    name="video"
                    id="video"
                    class="form-control"
                >
            </div>

            <button type="submit" class="btn btn-primary">Uložiť</button>
        </form>
    </div>
@endsection

<!-- Validacia na strane klienta -->
@section('scripts')
    <script>
        document.querySelector('#createForm').addEventListener('submit', function(e) {
            const title   = document.querySelector('#title');
            const content = document.querySelector('#content');

            if (!title.value.trim() || !content.value.trim()) {
                e.preventDefault();
                alert('Všetky polia sú povinné!');
            }
        });
    </script>
@endsection
