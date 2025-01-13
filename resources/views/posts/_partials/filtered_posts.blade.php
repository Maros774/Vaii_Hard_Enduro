@forelse($posts as $post)
    <div class="card mb-3">
        <div class="card-body">
            <h5>{{ $post->title }}</h5>
            <p>{{ $post->content }}</p>

            <!-- Obrázok -->
            @if($post->image_path)
                <img
                    src="{{ asset('storage/' . $post->image_path) }}"
                    alt="Obrázok príspevku"
                    class="img-fluid mt-2"
                    style="max-width:300px;"
                >
            @endif

            <!-- Video -->
            @if($post->video_path)
                <video controls style="max-width:400px;" class="mt-2">
                    <source src="{{ asset('storage/' . $post->video_path) }}" type="video/mp4">
                </video>
            @endif

            <!-- Lajky (ak používaš) -->
            <div class="mt-2">
                <button
                    class="btn btn-primary btn-sm like-button"
                    data-id="{{ $post->id }}"
                >
                    Like
                </button>
                <span id="like-count-{{ $post->id }}">
                    {{ $post->likes }}
                </span> Lajkov
            </div>

            <!-- Tlačidlá Upraviť / Vymazať (ak user je autor alebo admin) -->
            @auth
                @if(auth()->id() === $post->user_id || auth()->user()->role === 'admin')
                    <div class="mt-2">
                        <a
                            href="{{ route('posts.edit', $post->id) }}"
                            class="btn btn-warning btn-sm"
                        >
                            Upraviť
                        </a>
                        <form
                            action="{{ route('posts.destroy', $post->id) }}"
                            method="POST"
                            style="display:inline;"
                        >
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Naozaj odstrániť tento príspevok?')"
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
    <p>Žiadne príspevky nespĺňajú filter.</p>
@endforelse
