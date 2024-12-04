import './bootstrap';
import '../sass/app.scss';

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('#search');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = this.value;
            fetch(`/posts/search?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    let html = '';
                    data.forEach(post => {
                        html += `<tr><td>${post.title}</td></tr>`;
                    });
                    document.querySelector('#postList').innerHTML = html;
                });
        });
    }
});
