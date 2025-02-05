@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Vitajte!</h4>
                    </div>
                    <div class="card-body text-center">
                        <!-- Kontrola, či je používateľ prihlásený -->
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

                            <!-- Odkazy pre prihlásených používateľov -->
                            <a href="{{ route('dashboard') }}" class="btn btn-primary me-2">Domov</a>
                            <a href="{{ route('posts.index') }}" class="btn btn-info me-2">Príspevky</a>
                            <a href="{{ route('about.index') }}" class="btn btn-secondary">O nás</a>

                            <hr>
                            <!-- Tlačidlo na odhlásenie -->
                            <a href="{{ route('logout') }}" class="btn btn-danger mt-3"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Odhlásiť sa
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @else
                            <!-- Obsah pre neprihlásených používateľov -->
                            <p class="text-muted">Prosím, prihláste sa, aby ste mohli používať všetky funkcie stránky.</p>
                            <a href="{{ route('login') }}" class="btn btn-primary me-2">Prihlásiť sa</a>
                            <a href="{{ route('register') }}" class="btn btn-success">Registrovať sa</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <h1>Sme nadšenci Hard Enduro a tu je niečo o nás.</h1>

            <p>
                Hard Enduro je extrémny šport, ktorý spočíva v jazde na motocykli v ťažkom teréne.
                Naša stránka je určená pre všetkých nadšencov tohto športu, ktorí chcú zdieľať svoje zážitky,
                fotografie a videá. Ak máte záujem o spoluprácu, neváhajte nás kontaktovať.


            <!-- obrázok -->
            <img
                src="{{ asset('media/about/images/KTM 300 EXC_HARDENDURO.jpg') }}"
                alt="Motorka v teréne"
                class="img-rounded img-fluid"
                style="max-width: 100%; height: auto;"
            >

        </div>
    </div>
@endsection
