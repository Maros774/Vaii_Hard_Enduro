@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Príspevky</h1>
        <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Nový príspevok</a>
        <input type="text" id="search" class="form-control mb-3" placeholder="Hľadajte podľa názvu...">
        <table class="table">
            <thead>
            <tr>
                <th>Názov</th>
                <th>Akcie</th>
            </tr>
            </thead>
            <tbody id="postList">
            @foreach ($posts as $post)
                <tr>
                    <td>{{ $post->title }}</td>
                    <td>
                        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary btn-sm">Zobraziť</a>
                        @if (auth()->check() && auth()->id() === $post->user_id)
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">Upraviť</a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Vymazať</button>
                            </form>
                        @else
                            <span>Nemáte oprávnenie upravovať tento príspevok</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.querySelector('#search');
            const postList = document.querySelector('#postList');

            searchInput.addEventListener('input', function () {
                const query = this.value.trim();

                if (!query) {
                    postList.innerHTML = `<tr><td colspan="2" class="text-center">Zadajte hľadaný text...</td></tr>`;
                    return;
                }

                fetch(`/posts/search?query=${encodeURIComponent(query)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Chyba pri spracovaní požiadavky.');
                        }
                        return response.json();
                    })
                    .then(result => {
                        const posts = result.data;
                        if (!Array.isArray(posts)) {
                            throw new Error('Odpoveď zo servera nie je vo formáte poľa.');
                        }

                        let html = '';
                        if (posts.length === 0) {
                            html = '<tr><td colspan="2" class="text-center">Žiadne výsledky.</td></tr>';
                        } else {
                            posts.forEach(post => {
                                html += `
                            <tr>
                                <td>${post.title}</td>
                                <td>
                                    <a href="/posts/${post.id}" class="btn btn-primary btn-sm">Zobraziť</a>
                                </td>
                            </tr>`;
                            });
                        }
                        postList.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Chyba pri vyhľadávaní:', error);
                        postList.innerHTML = '<tr><td colspan="2" class="text-center">Nastala chyba pri vyhľadávaní.</td></tr>';
                    });
            });
        });
    </script>
@endsection
