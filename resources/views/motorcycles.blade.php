@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Motocykle</h2>

        <!-- Pridať nový motocykel (len pre admina) -->
        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('motorcycles.create') }}" class="btn btn-success mb-3">Pridať nový motocykel</a>
            @endif
        @endauth

        <!-- Zobrazenie existujúcich motocyklov -->
        @if($motorcycles->isEmpty())
            <p>Žiadne motocykle zatiaľ neboli pridané.</p>
        @else
            <div>
                <ul>
                    @foreach($motorcycles as $motorcycle)
                        <li>
                            <strong>{{ $motorcycle->name }}</strong> - {{ $motorcycle->description }}
                            @if($motorcycle->image)
                                <div>
                                    <img src="{{ asset('storage/' . $motorcycle->image) }}" alt="{{ $motorcycle->name }}" class="img-fluid" style="max-width: 200px;">
                                </div>
                            @endif

                            <!-- Akcie pre admina -->
                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <div class="mt-2">
                                        <a href="{{ route('motorcycles.edit', $motorcycle->id) }}" class="btn btn-warning btn-sm">Upraviť</a>
                                        <form action="{{ route('motorcycles.destroy', $motorcycle->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Vymazať</button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection
