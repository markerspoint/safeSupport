
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('a');
    const loadingSpinner = document.querySelector('.loading-spinner');

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!this.href.includes('#')) {
                e.preventDefault();
                loadingSpinner.style.display = 'flex';
                setTimeout(() => {
                    window.location.href = this.href;
                }, 100); // Changed from 500 to 100ms
            }
        });
    });
});
