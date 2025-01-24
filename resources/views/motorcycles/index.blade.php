@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Motocykle</h2>

        <!-- Pridať tlačidlo na pridanie nového motocyklu (len pre admina) -->
        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('motorcycles.create') }}" class="btn btn-success mb-3">Pridať nový motocykel</a>
            @endif
        @endauth

        <!-- Zobrazenie existujúcich motocyklov -->
        @foreach($motorcycles as $motorcycle)
            <div class="card mb-3">
                <div class="card-body">
                    <h5>{{ $motorcycle->name }}</h5>
                    <p>{{ $motorcycle->description }}</p>

                    @if($motorcycle->image)
                        <!-- Obrázok s "media-rounded" -->
                        <img
                            src="{{ asset('storage/' . $motorcycle->image) }}"
                            alt="{{ $motorcycle->name }}"
                            class="img-fluid mb-3 media-rounded"
                        >
                    @endif

                    <!-- Akcie len pre admina -->
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('motorcycles.edit', $motorcycle->id) }}" class="btn btn-warning btn-sm">Upraviť</a>
                            <form action="{{ route('motorcycles.destroy', $motorcycle->id) }}" method="POST" style="display:inline;">
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
