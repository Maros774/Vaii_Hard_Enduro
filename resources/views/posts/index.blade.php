@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Príspevky</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @auth
            <a href="{{ route('posts.create') }}" class="btn btn-success mb-3">
                Pridať nový príspevok
            </a>
        @endauth

        <!-- Filter -->
        <div class="row mb-3">
            <div class="col-md-3">
                <input type="text" id="authorFilter" class="form-control" placeholder="ID autora">
            </div>
            <div class="col-md-3">
                <input type="date" id="dateFilter" class="form-control">
            </div>
            <div class="col-md-4">
                <input type="text" id="searchTerm" class="form-control"
                       placeholder="Hľadaj v názve/obsahu">
            </div>
            <div class="col-md-2">
                <button id="filterBtn" class="btn btn-primary w-100">
                    Filtrovať
                </button>
            </div>
        </div>

        <!-- Zobrazenie príspevkov (prvýkrát normálne) -->
        <div id="postList">
            @include('posts._partials.filtered_posts', ['posts' => $posts])
        </div>
    </div>
@endsection
