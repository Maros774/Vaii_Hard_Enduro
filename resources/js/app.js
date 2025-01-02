document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('#search');
    const postList = document.querySelector('#postList');

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
});
