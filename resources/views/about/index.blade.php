@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>O nás</h2>
        @foreach($content as $about)
            <div class="card mb-3">
                <div class="card-body">
                    <h5>{{ $about->title }}</h5>
                    <p>{{ $about->content }}</p>
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('about.edit', $about->id) }}" class="btn btn-warning btn-sm">Upraviť</a>
                            <form action="{{ route('about.destroy', $about->id) }}" method="POST" style="display:inline;">
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
