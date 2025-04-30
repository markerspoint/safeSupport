<?php
session_start();
include_once('../includes/db.php'); // $pdo

// Handle Add
if (isset($_POST['add_announcement'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    if ($title !== '' && $content !== '') {
        $stmt = $pdo->prepare("INSERT INTO announcements (title, content) VALUES (:title, :content)");
        $stmt->execute([':title' => $title, ':content' => $content]);
        $_SESSION['message'] = "Announcement added successfully!";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Edit
if (isset($_POST['edit_announcement'])) {
    $id = intval($_POST['id']);
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    if ($id && $title !== '' && $content !== '') {
        $stmt = $pdo->prepare("UPDATE announcements SET title = :title, content = :content WHERE id = :id");
        $stmt->execute([':title' => $title, ':content' => $content, ':id' => $id]);
        $_SESSION['message'] = "Announcement updated successfully!";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Delete
if (isset($_POST['delete_announcement'])) {
    $id = intval($_POST['id']);
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM announcements WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $_SESSION['message'] = "Announcement deleted successfully!";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch all announcements
$stmt = $pdo->query("SELECT * FROM announcements ORDER BY id DESC");
$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

// For editing
$edit_announcement = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM announcements WHERE id = :id");
    $stmt->execute([':id' => intval($_GET['edit'])]);
    $edit_announcement = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Announcements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 700px;">
    <h2 class="mb-4 text-center">Announcements</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- Add/Edit Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST">
                <?php if ($edit_announcement): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_announcement['id']; ?>">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control" required value="<?php echo htmlspecialchars($edit_announcement['title']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Edit Announcement</label>
                        <textarea name="content" id="content" class="form-control" rows="3" required><?php echo htmlspecialchars($edit_announcement['content']); ?></textarea>
                    </div>
                    <button type="submit" name="edit_announcement" class="btn btn-primary">Update</button>
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-secondary">Cancel</a>
                <?php else: ?>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">New Announcement</label>
                        <textarea name="content" id="content" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="add_announcement" class="btn btn-success">Add Announcement</button>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Announcements List as Cards -->
    <div class="row">
        <?php foreach ($announcements as $a): ?>
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2"><?php echo htmlspecialchars($a['title']); ?> <span class="text-muted" style="font-size:0.9em;">#<?php echo $a['id']; ?></span></h6>
                            <p class="card-text mb-0"><?php echo htmlspecialchars($a['content']); ?></p>
                        </div>
                        <div class="ms-3">
                            <a href="?edit=<?php echo $a['id']; ?>" class="btn btn-sm btn-warning mb-1">Edit</a>
                            <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Delete this announcement?');">
                                <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                                <button type="submit" name="delete_announcement" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($announcements)): ?>
            <div class="col-12 text-center text-muted">No announcements found.</div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>