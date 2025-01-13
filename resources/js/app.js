console.log('Hello from app.js');

document.addEventListener('DOMContentLoaded', function () {

    // Validácia formulárov
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', e => {
            const inputs = form.querySelectorAll('input[required], textarea[required]');
            let valid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!valid) {
                e.preventDefault();
                alert('Vyplňte všetky povinné polia.');
            }
        });
    });

    // Funkcia na pripojenie listenerov na tlačidlá Like
    function attachLikeListeners() {
        document.querySelectorAll('.like-button').forEach(button => {
            button.removeEventListener('click', likeButtonHandler); // Avoid duplicate listeners
            button.addEventListener('click', likeButtonHandler);
        });
    }

    // Handler pre tlačidlá Like
    function likeButtonHandler(event) {
        const button = event.currentTarget;
        const postId = button.dataset.id;

        fetch(`/posts/${postId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Aktualizácia počtu lajkov
                const likeCount = document.querySelector(`#like-count-${postId}`);
                if (likeCount) {
                    if (data && data.likes !== undefined) {
                        likeCount.textContent = data.likes;
                    } else {
                        console.error("Likes property is missing in the response data.");
                    }
                }
            })
            .catch(error => {
                console.error("Error during liking:", error);
            });
    }

    // Filtrovanie príspevkov
    const authorFilter = document.querySelector('#authorFilter');
    const dateFilter = document.querySelector('#dateFilter');
    const filterBtn = document.querySelector('#filterBtn');

    if (filterBtn) {
        filterBtn.addEventListener('click', function () {
            const author = authorFilter ? authorFilter.value.trim() : '';
            const date = dateFilter ? dateFilter.value : '';

            const params = new URLSearchParams();
            if (author) params.append('author', author);
            if (date) params.append('date', date);

            fetch(`/posts?${params.toString()}`, {
                headers: {
                    'Accept': 'application/json',
                },
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(result => {
                    postList.innerHTML = '';
                    if (!result || !result.data || result.data.length === 0) {
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

                        // Re-attach Like listeners for new posts
                        attachLikeListeners();
                    }
                })
                .catch(error => {
                    console.error('Error fetching filtered posts:', error);
                });
        });
    }

    // Initial attachment of Like listeners
    attachLikeListeners();
});
