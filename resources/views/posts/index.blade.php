@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Príspevky</h2>

        <!-- Tlačidlo na pridanie nového príspevku -->
        @auth
            <a href="{{ route('posts.create') }}" class="btn btn-success mb-3">Pridať nový príspevok</a>
        @endauth

        <!-- Vyhľadávacie pole -->
        <input type="text" id="search" class="form-control mb-3" placeholder="Vyhľadajte príspevky...">

        <!-- Zoznam príspevkov -->
        <div id="postList">
            @foreach ($posts as $post)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text">{{ $post->content }}</p>

                        <!-- Zobrazenie fotky -->
                        @if ($post->image_path)
                            <img src="{{ asset('storage/' . $post->image_path) }}" alt="Obrázok príspevku" class="img-fluid mt-3">
                        @endif

                        <!-- Zobrazenie videa -->
                        @if ($post->video_path)
                            <video controls class="mt-3 w-100">
                                <source src="{{ asset('storage/' . $post->video_path) }}" type="video/mp4">
                                Váš prehliadač nepodporuje prehrávanie videa.
                            </video>
                        @endif

                        <!-- Akcie pre oprávneného používateľa (autora príspevku) -->
                        @auth
                            @if (auth()->id() === $post->user_id)
                                <div class="mt-3">
                                    <!-- Tlačidlo na úpravu -->
                                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">Upraviť</a>

                                    <!-- Tlačidlo na vymazanie -->
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Vymazať</button>
                                    </form>
                                </div>
                            @endif
                        @endauth

                        <!-- Lajkovanie -->
                        <div class="mt-2">
                            <button class="btn btn-primary btn-sm like-button" data-id="{{ $post->id }}">Like</button>
                            <span id="like-count-{{ $post->id }}">{{ $post->likes }}</span> Lajkov
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.querySelector('#search');
            const postList = document.querySelector('#postList');

            // Vyhľadávanie
            searchInput.addEventListener('input', function () {
                const query = this.value.trim();

                fetch(`/posts/search?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(result => {
                        postList.innerHTML = '';
                        if (result.data.length === 0) {
                            postList.innerHTML = '<p class="text-center">Žiadne výsledky.</p>';
                        } else {
                            result.data.forEach(post => {
                                postList.innerHTML += `
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">${post.title}</h5>
                                            <p class="card-text">${post.content}</p>
                                            <button class="btn btn-primary btn-sm like-button" data-id="${post.id}">Like</button>
                                            <span id="like-count-${post.id}">${post.likes}</span> Lajkov
                                        </div>
                                    </div>
                                `;
                            });
                        }
                    });
            });

            // Lajkovanie
            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('like-button')) {
                    const postId = e.target.dataset.id;

                    fetch(`/posts/${postId}/like`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            document.querySelector(`#like-count-${postId}`).textContent = data.likes;
                        })
                        .catch(error => console.error('Chyba pri lajkovaní:', error));
                }
            });
        });
    </script>
@endsection
