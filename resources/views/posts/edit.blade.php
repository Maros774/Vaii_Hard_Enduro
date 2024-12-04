@extends('layouts.app')

@section('content')
    <h1>{{ isset($post) ? 'Upraviť príspevok' : 'Nový príspevok' }}</h1>
    <form id="postForm" action="{{ isset($post) ? route('posts.update', $post->id) : route('posts.store') }}" method="POST">
        @csrf
        @if(isset($post))
            @method('PUT')
        @endif
        <div class="mb-3">
            <label for="title" class="form-label">Názov</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $post->title ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Obsah</label>
            <textarea class="form-control" id="content" name="content" rows="5" required>{{ $post->content ?? '' }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">{{ isset($post) ? 'Uložiť' : 'Vytvoriť' }}</button>
    </form>
@endsection

@section('scripts')
    <script>
        // Pridanie validácie do formulára
        document.querySelector('#postForm').addEventListener('submit', function(e) {
            const title = document.querySelector('#title');
            const content = document.querySelector('#content');

            // Kontrola, či sú všetky polia vyplnené
            if (!title.value.trim() || !content.value.trim()) {
                e.preventDefault();
                alert('Všetky polia sú povinné!');
            }
        });
    </script>
@endsection
