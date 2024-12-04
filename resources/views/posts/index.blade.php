@extends('layouts.app')

@section('content')
    <h1>Príspevky</h1>
    <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Nový príspevok</a>
    <input type="text" id="search" class="form-control my-3" placeholder="Hľadajte príspevok...">

    @if ($posts->isEmpty())
        <p class="text-center">Zatiaľ neexistujú žiadne príspevky.</p>
    @else
        <div id="results">
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
                            @if ($post->user_id === auth()->id())
                                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning">Upraviť</a>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Vymazať</button>
                                </form>
                            @else
                                <span class="text-muted">Nemáte oprávnenie upravovať tento príspevok</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        // Vyhľadávacia funkcia
        document.querySelector('#search').addEventListener('input', function() {
            const query = this.value;

            // Posielame požiadavku na server
            fetch(`/posts/search?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    let html = '';
                    if (data.length === 0) {
                        html = '<tr><td colspan="2" class="text-center">Žiadne príspevky nevyhovujú vyhľadávaniu.</td></tr>';
                    } else {
                        data.forEach(post => {
                            html += `
                            <tr>
                                <td>${post.title}</td>
                                <td>
                                    ${post.user_id === {{ auth()->id() }} ? `
                                        <a href="/posts/${post.id}/edit" class="btn btn-warning">Upraviť</a>
                                        <form action="/posts/${post.id}" method="POST" style="display:inline;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <button type="submit" class="btn btn-danger">Vymazať</button>
                                        </form>
                                    ` : `<span class="text-muted">Nemáte oprávnenie upravovať tento príspevok</span>`}
                                </td>
                            </tr>
                            `;
                        });
                    }
                    document.querySelector('#postList').innerHTML = html;
                });
        });
    </script>
@endsection
