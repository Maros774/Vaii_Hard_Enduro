@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('  ') }}</div>

                    <div class="card-body">
                        <!-- Kontrola, či je používateľ prihlásený -->
                        @auth
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <h4>Vitaj, {{ Auth::user()->name }}!</h4>
                            <p>{{ __('Ste prihlásený!') }}</p>

                            <hr>
                            <!-- Odkazy -->
                            <!-- <a href="{{ route('home') }}" class="btn btn-primary">Domov</a>
                            <a href="{{ route('about') }}" class="btn btn-secondary">O nás</a>
                            <a href="{{ route('posts.index') }}" class="btn btn-info">Príspevky</a> -->

                            <hr>
                            <!-- Logout -->
                            <a href="{{ route('logout') }}" class="btn btn-danger"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Odhlásiť sa') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @else
                            <!-- Ak používateľ nie je prihlásený -->
                            <p>{{ __('Prosím, prihláste sa, aby ste mohli používať nástennú tabuľu.') }}</p>
                            <a href="{{ route('login') }}" class="btn btn-primary">Prihlásiť sa</a>
                            <a href="{{ route('register') }}" class="btn btn-success">Registrovať sa</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
