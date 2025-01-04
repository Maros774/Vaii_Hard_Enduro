@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Upraviť obsah</h2>
        <form action="{{ route('about.update', $about->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="title" class="form-label">Názov</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ $about->title }}" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Obsah</label>
                <textarea name="content" id="content" class="form-control" rows="5" required>{{ $about->content }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Aktualizovať</button>
        </form>
    </div>
@endsection
