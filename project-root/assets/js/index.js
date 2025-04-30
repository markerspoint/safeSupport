/**
 * Handles the carousel indicator updates for the hero section
 */
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('heroCarousel');
    const indicators = document.querySelectorAll('[data-bs-target="#heroCarousel"]');
    
    // Function to update indicator colors
    function updateIndicators(activeIndex) {
        indicators.forEach((indicator, index) => {
            if (index === activeIndex) {
                indicator.style.backgroundColor = '#c1703d'; // Darker color for active slide
            } else {
                indicator.style.backgroundColor = '#e3b766'; // Original color for inactive slides
            }
        });
    }

    // Set initial state
    updateIndicators(0);

    // Listen for slide change
    carousel.addEventListener('slide.bs.carousel', function(e) {
        updateIndicators(e.to);
    });
});
