<?php
session_start();
require_once('../includes/db.php');

include('../admindashboard/adminHeader.php');

// Handle form submission to add new resource
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $link = $_POST['link'];

    $sql = "INSERT INTO resources (title, type, description, link) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$title, $type, $description, $link]);

    header("Location: adminResourceLibrary.php"); // Corrected the redirection URL
    exit;
}

// Fetch all resources
$sql = "SELECT * FROM resources ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mental Health Resources</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>
<body>

<div class="container mt-4 px-4" style="margin-left: 220px; max-width: calc(100% - 220px);">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Mental Health Resources</h1>
    </div>

    <!-- Button to trigger the modal (Now below the title) -->
    <div class="mb-4">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addResourceModal">
            <i class="bi bi-plus-circle"></i> Add New Resource
        </button>
    </div>

    <!-- Filter Buttons -->
    <div class="btn-group mb-4" role="group">
        <button type="button" class="btn btn-primary" onclick="filterResources('all')">All</button>
        <button type="button" class="btn btn-secondary" onclick="filterResources('article')">Articles</button>
        <button type="button" class="btn btn-secondary" onclick="filterResources('video')">Videos</button>
        <button type="button" class="btn btn-secondary" onclick="filterResources('tool')">Self-Help Tools</button>
    </div>

    <!-- Resource Cards -->
    <div class="row" id="resources-container">
        <?php foreach ($resources as $resource): ?>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4 resource-card" data-type="<?php echo htmlspecialchars($resource['type']); ?>">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($resource['title']); ?></h5>
                        <p class="card-text"><strong>Type:</strong> <?php echo htmlspecialchars($resource['type']); ?></p>

                        <!-- Truncated description -->
                        <p class="card-text truncated-description">
                            <?php echo substr(htmlspecialchars($resource['description']), 0, 100); ?>... 
                            <a href="javascript:void(0)" onclick="toggleDescription(this)">Show more</a>
                        </p>

                        <!-- Full description -->
                        <p class="card-text full-description" style="display:none;"><?php echo htmlspecialchars($resource['description']); ?></p>

                        <a href="<?php echo htmlspecialchars($resource['link']); ?>" class="btn btn-primary" target="_blank">Learn more</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Add Resource Modal -->
<div class="modal fade" id="addResourceModal" tabindex="-1" role="dialog" aria-labelledby="addResourceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addResourceModalLabel">Add a New Resource</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control" id="type" name="type" required>
                            <option value="article">Article</option>
                            <option value="video">Video</option>
                            <option value="tool">Self-Help Tool</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="link">Link</label>
                        <input type="text" class="form-control" id="link" name="link" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Resource</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Function to filter resources by type
    function filterResources(type) {
        const cards = document.querySelectorAll('.resource-card');
        cards.forEach(card => {
            if (type === 'all' || card.getAttribute('data-type') === type) {
                card.style.display = "block"; // Show the card
            } else {
                card.style.display = "none"; // Hide the card
            }
        });
    }

    // Function to toggle the description visibility
    function toggleDescription(button) {
        const fullDescription = button.previousElementSibling.nextElementSibling;
        const truncatedDescription = button.previousElementSibling;
        
        // Toggle between showing and hiding
        if (fullDescription.style.display === "none") {
            fullDescription.style.display = "inline";
            truncatedDescription.style.display = "none";
            button.innerHTML = "Show less";
        } else {
            fullDescription.style.display = "none";
            truncatedDescription.style.display = "inline";
            button.innerHTML = "Show more";
        }
    }
</script>

</body>
</html>
