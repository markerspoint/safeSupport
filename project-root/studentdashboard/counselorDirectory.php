<?php
session_start();
require_once('../includes/db.php');

// Fetch counselor data
function getCounselors($pdo) {
    $sql = "SELECT * FROM counselors WHERE active = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$counselors = getCounselors($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counselor Directory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function filterCounselors() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let cards = document.getElementsByClassName("counselor-card");

            for (let i = 0; i < cards.length; i++) {
                let card = cards[i];
                let textContent = card.textContent.toLowerCase();

                if (textContent.includes(input)) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            }
        }
    </script>
</head>
<body>
<?php include('../includes/dsheader.php'); ?>

<!-- Main Content Wrapper -->
<div class="container mt-4 px-4" style="margin-left: 220px; max-width: calc(100% - 220px);">
    <h1 class="text-center">Counselor Directory</h1>

    <!-- Search Filter -->
    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Search by name, specialization, or availability..." onkeyup="filterCounselors()">
    </div>

    <div class="row">
        <?php foreach ($counselors as $counselor): ?>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4 counselor-card">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <h5 class="card-title"><?php echo htmlspecialchars($counselor['name']); ?></h5>
                        <p class="card-text"><strong>Specialization:</strong> <?php echo htmlspecialchars($counselor['specialization']); ?></p>
                        <p class="card-text"><strong>Availability:</strong> <?php echo htmlspecialchars($counselor['availability']); ?></p>
                        <p class="card-text"><strong>Experience:</strong> <?php echo htmlspecialchars($counselor['experience']); ?> years</p>
                        <p class="card-text"><strong>Bio:</strong> <?php echo htmlspecialchars($counselor['bio']); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
