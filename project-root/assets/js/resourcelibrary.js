// Function to filter resources by type
function filterResources(type) {
    const cards = document.querySelectorAll('.resource-card');
    cards.forEach(card => {
        if (type === 'all' || card.getAttribute('data-type') === type) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
}

// Initialize modal and close button functionality
$(document).ready(function() {
    // Initialize modal
    $('#descriptionModal').modal({
        keyboard: true,
        backdrop: true,
        show: false
    });

    // Add click handler for close button
    $('.close').click(function() {
        $('#descriptionModal').modal('hide');
    });

    // Load video thumbnails
    document.querySelectorAll('.video-thumb').forEach(img => {
        const videoUrl = img.getAttribute('data-video-url');
        const thumbnailUrl = getVideoThumbnail(videoUrl);
        if (thumbnailUrl) {
            img.src = thumbnailUrl;
        }
    });
});

// Function to show description in modal
function showDescription(title, description) {
    document.getElementById('descriptionModalLabel').textContent = title;
    document.getElementById('modalDescription').textContent = description;
    $('#descriptionModal').modal('show');
}

function getYoutubeVideoId(url) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);
    return (match && match[2].length === 11) ? match[2] : null;
}

function getVideoThumbnail(url) {
    const videoId = getYoutubeVideoId(url);
    return videoId ? `https://img.youtube.com/vi/${videoId}/mqdefault.jpg` : null;
}
