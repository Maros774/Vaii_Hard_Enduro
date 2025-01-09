@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Upraviť motocykel</h2>
        <form action="{{ route('motorcycles.update', $motorcycle->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Názov</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $motorcycle->name }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Popis</label>
                <textarea name="description" id="description" class="form-control" rows="5" required>{{ $motorcycle->description }}</textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Obrázok</label>
                <input type="file" name="image" id="image" class="form-control">
                @if($motorcycle->image)
                    <p>Aktuálny obrázok: <a href="{{ asset('storage/' . $motorcycle->image) }}" target="_blank">Zobraziť</a></p>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Aktualizovať</button>
        </form>
    </div>
@endsection
