console.log('Hello from app.js');

document.addEventListener('DOMContentLoaded', function () {
    /**
     * 1) VALIDÁCIA FORMULÁROV
     *
     * Všetkým <form> v projekte, ktoré majú required inputy/textarea,
     * pridávame jednoduchú validáciu, aby neodoslali prázdne polia.
     */
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

    /**
     * 2) LIKE BUTTONS
     *
     * Funkcia na pripojenie "click" listenerov na všetky tlačidlá s triedou .like-button.
     */
    function attachLikeListeners() {
        document.querySelectorAll('.like-button').forEach(button => {
            button.removeEventListener('click', likeButtonHandler); // pre istotu
            button.addEventListener('click', likeButtonHandler);
        });
    }

    function likeButtonHandler(event) {
        const button = event.currentTarget;
        const postId = button.dataset.id;

        fetch(`/posts/${postId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest', // AJ bod
                'Accept': 'application/json'
            }
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
                    if (data && typeof data.likes !== 'undefined') {
                        likeCount.textContent = data.likes;
                    } else {
                        console.error('Likes property is missing in the response data.');
                    }
                }
            })
            .catch(error => {
                console.error('Error during liking:', error);
            });
    }

    /**
     * 3) FILTROVANIE PRÍSPEVKOV
     *
     * Volá /posts?author=..., date=... s AJAX, a očakáva partial HTML.
     */
    const authorFilter = document.querySelector('#authorFilter');
    const dateFilter   = document.querySelector('#dateFilter');
    const filterBtn    = document.querySelector('#filterBtn');
    const postList     = document.querySelector('#postList');

    if (filterBtn && postList) {
        filterBtn.addEventListener('click', function () {
            const params = new URLSearchParams();
            if (authorFilter && authorFilter.value.trim()) {
                params.append('author', authorFilter.value.trim());
            }
            if (dateFilter && dateFilter.value) {
                params.append('date', dateFilter.value);
            }

            // Voláme /posts?author=X&date=Y v móde, kde vyžiadame partial HTML
            fetch('/posts?' + params.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.text(); // partial HTML
                })
                .then(html => {
                    // Nahradíme obsah #postList
                    postList.innerHTML = html;
                    // Znova pripojíme like eventy na nové tlačidlá
                    attachLikeListeners();
                })
                .catch(error => {
                    console.error('Error fetching filtered posts:', error);
                });
        });
    }

    // Na prvé načítanie pripoj "like" eventy, aby fungovali v indexe aj bez filtra
    attachLikeListeners();
});
