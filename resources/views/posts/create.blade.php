@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Vytvoriť príspevok</h2>
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Názov</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Obsah</label>
                <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Pridať fotku</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>
            <div class="mb-3">
                <label for="video" class="form-label">Pridať video</label>
                <input type="file" class="form-control" id="video" name="video">
            </div>
            <button type="submit" class="btn btn-primary">Uložiť</button>
        </form>
    </div>
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




