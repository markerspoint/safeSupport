document.addEventListener('DOMContentLoaded', function() {
    // Get all elements with the class 'animate-scroll'
    const elements = document.querySelectorAll('.animate-scroll');

    // Create an IntersectionObserver to detect when the element comes into view
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-scroll-in-view');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.5  // Trigger the animation when 50% of the element is in view
    });

    // Observe each element
    elements.forEach(element => {
        observer.observe(element);
    });
});
