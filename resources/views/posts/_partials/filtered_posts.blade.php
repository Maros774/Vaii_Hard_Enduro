@forelse($posts as $post)
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ $post->title }}</h5>
            <p class="card-text">{{ $post->content }}</p>

            @if($post->image_path)
                <img
                    src="{{ asset('storage/' . $post->image_path) }}"
                    alt="Obrázok príspevku"
                    class="img-fluid media-rounded"
                    style="max-width:300px;"
                >
            @endif

            @if($post->video_path)
                <video
                    controls
                    style="max-width:400px;"
                    class="mt-2 media-rounded"
                >
                    <source src="{{ asset('storage/' . $post->video_path) }}" type="video/mp4">
                </video>
            @endif

            <div class="mt-2">
                @auth
                    <button class="btn btn-primary btn-sm like-button" data-id="{{ $post->id }}">
                        Like
                    </button>
                @endauth
                <span id="like-count-{{ $post->id }}">{{ $post->likes }}</span> Lajkov
            </div>

            <!-- Link na detail -->
            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-info btn-sm mt-2">
                Detaily
            </a>

            @auth
                <!-- Edit, Delete len pre autora/admina -->
                @if(auth()->id() === $post->user_id || (auth()->user()->role ?? '') === 'admin')
                    <div class="mt-2">
                        <a href="{{ route('posts.edit', $post->id) }}"
                           class="btn btn-warning btn-sm"
                        >Upraviť</a>

                        <form action="{{ route('posts.destroy', $post->id) }}"
                              method="POST"
                              style="display:inline;"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Naozaj odstrániť?')"
                            >
                                Vymazať
                            </button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </div>
@empty
    <p class="text-center">Žiadne výsledky.</p>
@endforelse
