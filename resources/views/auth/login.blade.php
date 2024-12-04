@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>{{ __('Prihlásenie') }}</h4>
                    </div>

                    <div class="card-body">
                        <!-- Zobrazenie chýb alebo správ -->
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Formulár na prihlásenie -->
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Emailová adresa') }}</label>
                                <input id="email" type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}"
                                       required autocomplete="email" autofocus>
                                @error('email')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <!-- Heslo -->
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Heslo') }}</label>
                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       name="password" required autocomplete="current-password">
                                @error('password')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <!-- Zapamätať si ma -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Zapamätať si ma') }}
                                </label>
                            </div>

                            <!-- Tlačidlá -->
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Prihlásiť sa') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="text-primary text-decoration-underline" href="{{ route('password.request') }}">
                                        {{ __('Zabudnuté heslo?') }}
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Registrovať sa -->
                <div class="text-center mt-3">
                    <p>Nemáte účet? <a href="{{ route('register') }}" class="text-primary text-decoration-underline">{{ __('Zaregistrujte sa tu') }}</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
