<?php
session_start();
require_once('../includes/db.php');

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
</head>
<body>
<?php include '../includes/dsheader.php'; ?>

<div class="container mt-4 px-4" style="margin-left: 220px; max-width: calc(100% - 220px);">
    <h1 class="mb-4">Mental Health Resources</h1>

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
                        <p class="card-text"><?php echo htmlspecialchars($resource['description']); ?></p>
                        <a href="<?php echo htmlspecialchars($resource['link']); ?>" class="btn btn-primary" target="_blank">Learn more</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
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
</script>

</body>
</html>
