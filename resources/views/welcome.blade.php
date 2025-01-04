@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Vitajte na stránke Hard Enduro!</h4>
                    </div>
                    <div class="card-body text-center">
                        @auth
                            <div class="mb-3">
                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <h5>Ahoj, {{ Auth::user()->name }}!</h5>
                                <p class="text-muted">Ste prihlásený a môžete používať všetky funkcie stránky.</p>
                            </div>
                            <a href="{{ route('dashboard') }}" class="btn btn-primary me-2">Dashboard</a>
                            <a href="{{ route('posts.index') }}" class="btn btn-info me-2">Príspevky</a>
                            <a href="{{ route('about') }}" class="btn btn-secondary">O nás</a>
                        @else
                            <p class="text-muted">Prihláste sa, aby ste mohli používať všetky funkcie.</p>
                            <a href="{{ route('login') }}" class="btn btn-primary me-2">Prihlásiť sa</a>
                            <a href="{{ route('register') }}" class="btn btn-success">Registrovať sa</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
