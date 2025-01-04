@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Pridať nový obsah</h2>
        <form action="{{ route('about.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Názov</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Obsah</label>
                <textarea name="content" id="content" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Odoslať</button>
        </form>
    </div>
@endsection
