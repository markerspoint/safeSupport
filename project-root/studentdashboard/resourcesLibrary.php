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
    <link rel="stylesheet" href="../assets/css/resourcelibrary.css">
</head>
<body>
<?php include '../includes/dsheader.php'; ?>

<div class="container mt-4 px-4" style="margin-left: 220px; max-width: calc(100% - 220px);">
    <h1 class="mb-4">Mental Health Resources</h1>

    <!-- Filter Buttons -->
    <div class="btn-group mb-4" role="group">
        <button type="button" class="btn filter-btn active" data-type="all" 
                style="background-color: #e3b766; color: white; border-right: 1px solid rgba(255,255,255,0.2);" 
                onclick="filterResources('all')">All</button>
        <button type="button" class="btn filter-btn" data-type="article" 
                style="background-color: #e3b766; color: white; border-right: 1px solid rgba(255,255,255,0.2);" 
                onclick="filterResources('article')">Articles</button>
        <button type="button" class="btn filter-btn" data-type="video" 
                style="background-color: #e3b766; color: white; border-right: 1px solid rgba(255,255,255,0.2);" 
                onclick="filterResources('video')">Videos</button>
        <button type="button" class="btn filter-btn" data-type="tool" 
                style="background-color: #e3b766; color: white;" 
                onclick="filterResources('tool')">Self-Help Tools</button>
    </div>

    <!-- Resource Cards -->
    <div class="row" id="resources-container">
        <?php foreach ($resources as $resource): ?>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4 resource-card" data-type="<?php echo htmlspecialchars($resource['type']); ?>">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <?php if ($resource['type'] === 'video'): ?>
                            <div class="video-thumbnail mb-3">
                                <img src="" 
                                     data-video-url="<?php echo htmlspecialchars($resource['link']); ?>" 
                                     class="card-img-top video-thumb" 
                                     alt="Video thumbnail">
                            </div>
                        <?php endif; ?>
                        <h5 class="card-title"><?php echo htmlspecialchars($resource['title']); ?></h5>
                        <p class="card-text"><strong>Type:</strong> <?php echo htmlspecialchars($resource['type']); ?></p>
                        <div class="description-container">
                            <p class="card-text description"><?php echo htmlspecialchars($resource['description']); ?></p>
                            <div class="fade-overlay"></div>
                        </div>
                        <button onclick="showDescription('<?php echo htmlspecialchars(addslashes($resource['title'])); ?>', '<?php echo htmlspecialchars(addslashes($resource['description'])); ?>')" class="btn btn-link mt-2 p-0" style="color: #e3b766;">Read More</button>
                        <a href="<?php echo htmlspecialchars($resource['link']); ?>" class="btn mt-auto" target="_blank" style="background-color: #e3b766; border-color: #e3b766; color: white;">Learn more</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Description Modal -->
<div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog" aria-labelledby="descriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="modalDescription"></p>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/resourcelibrary.js"></script>

</body>
</html>
