// DOM Ready
document.addEventListener('DOMContentLoaded', function () {
    // Vyhľadávanie príspevkov
    const searchInput = document.querySelector('#search');
    const postList = document.querySelector('#postList');

    if (searchInput && postList) {
        searchInput.addEventListener('input', function () {
            const query = this.value.trim();

            if (!query) {
                postList.innerHTML = `
                    <tr>
                        <td colspan="2" class="text-center">Zadajte hľadaný text...</td>
                    </tr>`;
                return;
            }

            fetch(`/posts/search?query=${encodeURIComponent(query)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data || !Array.isArray(data.data)) {
                        throw new Error("Formát odpovede API nie je správny.");
                    }
                    let html = '';
                    data.data.forEach(post => {
                        html += `<tr><td>${post.title}</td></tr>`;
                    });
                    postList.innerHTML = html;
                })
                .catch(error => {
                    console.error("Chyba pri vyhľadávaní:", error);
                    postList.innerHTML = '<tr><td colspan="2" class="text-center">Nastala chyba pri načítavaní príspevkov.</td></tr>';
                });
        });
    }

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

    // Lajkovanie príspevkov
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', () => {
            const postId = button.dataset.postId;

            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
            })
                .then(response => response.json())
                .then(data => {
                    // Aktualizácia počtu lajkov
                    const likeCount = document.querySelector(`#like-count-${postId}`);
                    if (likeCount) {
                        likeCount.textContent = data.likes;
                    }
                })
                .catch(error => {
                    console.error("Chyba pri lajkovaní:", error);
                });
        });
    });
});
