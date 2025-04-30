<?php
include_once('../includes/db.php'); // This sets up $pdo

$announcement = null;
$stmt = $pdo->query("SELECT title, content FROM announcements ORDER BY id DESC LIMIT 1");
if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $announcement = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>

    <!-- Bootstrap 5.3.0 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/announcement.css">
</head>
<body>

<main class="py-4">
    <div class="container d-flex justify-content-center">
        <div class="card shadow-sm announcement-card w-100" style="max-width: 600px;">
            <div class="card-header text-white" style="background-color: #0b6043;">
                <h5 class="mb-0"><i class="fas fa-bullhorn me-2"></i>Announcement</h5>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($announcement)): ?>
                    <h5 class="card-title"><?php echo htmlspecialchars($announcement['title']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($announcement['content']); ?></p>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" aria-label="Close" onclick="this.closest('.card').style.display='none';"></button>
                <?php else: ?>
                    <div class="text-center text-muted w-100">No announcements at the moment.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
