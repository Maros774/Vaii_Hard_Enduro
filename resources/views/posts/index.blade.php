@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Príspevky</h2>

        @auth
            <a href="{{ route('posts.create') }}" class="btn btn-success mb-3">
                Pridať nový príspevok
            </a>
        @endauth

        <!-- Filter a triedenie -->
        <div class="row mb-3">
            <div class="col-md-3">
                <input
                    type="text"
                    id="authorFilter"
                    class="form-control"
                    placeholder="Filter podľa autora (ID)"
                >
            </div>
            <div class="col-md-3">
                <input
                    type="date"
                    id="dateFilter"
                    class="form-control"
                >
            </div>
            <div class="col-md-3">
                <select id="sortBy" class="form-control">
                    <option value="">Bez triedenia</option>
                    <option value="title">Názov</option>
                    <option value="created_at">Dátum</option>
                    <option value="likes">Počet lajkov</option>
                </select>
            </div>
            <div class="col-md-1">
                <select id="direction" class="form-control">
                    <option value="asc">ASC</option>
                    <option value="desc">DESC</option>
                </select>
            </div>
            <div class="col-md-2">
                <button id="filterBtn" class="btn btn-primary w-100">
                    Filtrovať
                </button>
            </div>
        </div>

        <!-- Prvá (server side) verzia príspevkov -->
        <div id="postList">
            @include('posts._partials.filtered_posts', ['posts' => $posts])
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtn = document.getElementById('filterBtn');
            const authorFilter = document.getElementById('authorFilter');
            const dateFilter = document.getElementById('dateFilter');
            const sortBySelect = document.getElementById('sortBy');
            const directionSelect = document.getElementById('direction');
            const postList = document.getElementById('postList');

            if (filterBtn) {
                filterBtn.addEventListener('click', function() {
                    const author = authorFilter.value.trim();
                    const date = dateFilter.value;
                    const sortBy = sortBySelect.value;
                    const dir = directionSelect.value;

                    const params = new URLSearchParams();
                    if (author)  params.append('author', author);
                    if (date)    params.append('date', date);
                    if (sortBy)  params.append('sortBy', sortBy);
                    if (dir)     params.append('direction', dir);

                    fetch('/posts?' + params.toString(), {
                        headers: {
                            // Očakávame partial (HTML)
                            'Accept': 'text/html'
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('HTTP ' + response.status);
                            }
                            return response.text(); // TEXT = partial HTML
                        })
                        .then(html => {
                            postList.innerHTML = html;
                            // Po nahratí nového HTML znovu pripoj Like eventy
                            attachLikeListeners();
                        })
                        .catch(error => {
                            console.error('Chyba pri filtrovaní príspevkov:', error);
                        });
                });
            }

            // LIKE - pripojiť eventy
            attachLikeListeners();

            function attachLikeListeners() {
                document.querySelectorAll('.like-button').forEach(btn => {
                    btn.removeEventListener('click', likeHandler);
                    btn.addEventListener('click', likeHandler);
                });
            }

            function likeHandler(e) {
                const postId = e.currentTarget.dataset.id;
                fetch(`/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                    .then(r => {
                        if (!r.ok) throw new Error('Chyba lajkovania: ' + r.status);
                        return r.json();
                    })
                    .then(data => {
                        if (data && data.likes !== undefined) {
                            const likeCountSpan = document.getElementById('like-count-' + postId);
                            if (likeCountSpan) {
                                likeCountSpan.textContent = data.likes;
                            }
                        }
                    })
                    .catch(err => console.error('Error during like:', err));
            }
        });
    </script>
@endsection
