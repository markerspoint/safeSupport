<?php
session_start();
include('../includes/db.php');


if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php"); // Adjust path if needed
    exit();
}

// Prevent browser from caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Handle appointment status updates
if (isset($_GET['update_appointment_id']) && isset($_GET['status'])) {
    $appointment_id = $_GET['update_appointment_id'];
    $status = $_GET['status'];

    // Update the status of the appointment
    $stmt = $pdo->prepare("UPDATE appointments SET status = :status WHERE id = :appointment_id");
    if ($stmt->execute([
        ':status' => $status,
        ':appointment_id' => $appointment_id
    ])) {
        $_SESSION['message'] = "Appointment $status successfully!";
        $_SESSION['message_type'] = "success"; // Success toast
    } else {
        $_SESSION['message'] = "Error updating appointment status.";
        $_SESSION['message_type'] = "danger"; // Error toast
    }
    header("Location: " . $_SERVER['PHP_SELF']); 
    exit();
}

// Handle appointment rejection with reason
if (isset($_POST['reject_appointment']) && isset($_POST['appointment_id']) && isset($_POST['rejection_reason'])) {
    $appointment_id = $_POST['appointment_id'];
    $rejection_reason = $_POST['rejection_reason'];
    
    try {
        // Update appointment status to rejected and store reason in notes field
        $stmt = $pdo->prepare("UPDATE appointments SET status = 'rejected', notes = :notes WHERE id = :appointment_id");
        $stmt->execute([
            ':notes' => $rejection_reason,
            ':appointment_id' => $appointment_id
        ]);
        
        // Set success message
        $_SESSION['message'] = "Appointment rejected successfully!";
        $_SESSION['message_type'] = "success";
    } catch (PDOException $e) {
        // Set error message
        $_SESSION['message'] = "Error rejecting appointment: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
    
    // Redirect to refresh the page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle multiple appointment deletion
if (isset($_POST['delete_selected'])) {
    if (isset($_POST['appointments']) && is_array($_POST['appointments']) && count($_POST['appointments']) > 0) {
        try {
            // Begin transaction
            $pdo->beginTransaction();
            
            // Create a prepared statement for each ID
            $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ?");
            
            // Execute for each selected ID
            $deleteCount = 0;
            foreach ($_POST['appointments'] as $id) {
                if ($stmt->execute([$id])) {
                    $deleteCount += $stmt->rowCount();
                }
            }
            
            // Commit transaction
            $pdo->commit();
            
            if ($deleteCount > 0) {
                $_SESSION['message'] = "$deleteCount appointments deleted successfully!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "No appointments were deleted.";
                $_SESSION['message_type'] = "warning";
            }
        } catch (PDOException $e) {
            // Roll back transaction on error
            $pdo->rollBack();
            $_SESSION['message'] = "Error deleting appointments: " . $e->getMessage();
            $_SESSION['message_type'] = "danger";
        }
    } else {
        $_SESSION['message'] = "No appointments selected for deletion.";
        $_SESSION['message_type'] = "warning";
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Pagination settings
$records_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Count total appointments for pagination
$total_query = $pdo->query("SELECT COUNT(*) FROM appointments");
$total_appointments = $total_query->fetchColumn();
$total_pages = ceil($total_appointments / $records_per_page);

// Fetch appointments with pagination
$appointmentQuery = $pdo->prepare("SELECT a.id, a.appointment_time, a.reason, 
    counselor.name AS counselor_name, 
    student.name AS student_name, 
    a.status 
    FROM appointments a 
    JOIN users student ON a.user_id = student.id 
    JOIN users counselor ON a.counselor_id = counselor.id 
    ORDER BY a.appointment_time DESC 
    LIMIT :offset, :records_per_page");

$appointmentQuery->bindParam(':offset', $offset, PDO::PARAM_INT);
$appointmentQuery->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
$appointmentQuery->execute();
$appointments = $appointmentQuery->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Add the custom CSS file -->
    <link rel="stylesheet" href="../assets/css/adashboard/adminappointments.css">
</head>

<!-- links of the adminheader -->
<?php include('../admindashboard/adminHead.php'); ?>


<script>
// Reload page if it's loaded from back/forward cache (bfcache)
window.addEventListener('pageshow', function (event) {
    if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
        // Page was restored from bfcache or navigated via back button
        window.location.reload();
    }
});
</script>


<body>
    <div class="dashboard-wrapper">
        <?php include('../admindashboard/adminHeader.php'); ?>

        <main class="adminAppointments-main">      
            <div class="navbar">
                <h1 class="navbar-brand">Appointments</h1>
            </div>    

            <div class="container mt-4 px-4">
                <div class="card-container row mb-4">
                    <!-- Total Appointments Card -->
                    <div class="col-md-4">
                        <div class="card card-total-appointments">
                            <div class="card-header-content">
                                <i class="bi bi-calendar-check"></i>
                                <h5 class="card-title">Total Appointments</h5>
                            </div>
                            <p class="card-text"><span>counts: </span><?php echo $total_appointments; ?></p>
                        </div>
                    </div>

                    <!-- Pending Appointments Card -->
                    <div class="col-md-4">
                        <div class="card card-pending-appointments">
                            <div class="card-header-content">
                                <i class="bi bi-hourglass-split"></i>
                                <h5 class="card-title">Pending Appointments</h5>
                            </div>
                            <p class="card-text"><span>counts: </span>
                                <?php
                                $pending_query = $pdo->query("SELECT COUNT(*) FROM appointments WHERE status = 'pending'");
                                echo $pending_query->fetchColumn();
                                ?>
                            </p>
                        </div>
                    </div>

                    <!-- Completed Appointments Card -->
                    <div class="col-md-4">
                        <div class="card card-completed-appointments">
                            <div class="card-header-content">
                                <i class="bi bi-check-circle"></i>
                                <h5 class="card-title">Accepted Appointments</h5>
                            </div>
                            <p class="card-text"><span>counts: </span>
                                <?php
                                $completed_query = $pdo->query("SELECT COUNT(*) FROM appointments WHERE status = 'accepted'");
                                echo $completed_query->fetchColumn();
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <section class="col-12">
                        <div class="card shadow-sm appointments-container">
                            <div class="card-header">
                                <h2 class="card-title mb-0">Manage Appointments</h2>
                            </div>
                            <div class="card-body">
                                <!-- Success/Error Toast Message -->
                                <?php if (isset($_SESSION['message'])): ?>
                                    <div class="toast-container">
                                        <div id="toastMessage" class="toast show align-items-center text-white bg-<?php echo $_SESSION['message_type']; ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
                                            <div class="d-flex">
                                                <div class="toast-body">
                                                    <?php echo $_SESSION['message']; ?>
                                                </div>
                                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        setTimeout(function() {
                                            var toastEl = document.getElementById("toastMessage");
                                            if (toastEl) {
                                                var toast = new bootstrap.Toast(toastEl);
                                                toast.hide();
                                            }
                                        }, 5000);
                                    </script>
                                    <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                                    <?php if (isset($_SESSION['show_reject_modal'])): ?>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                var rejectModal = new bootstrap.Modal(document.getElementById("rejectModal<?php echo $_SESSION['show_reject_modal']; ?>"));
                                                rejectModal.show();
                                            });
                                        </script>
                                        <?php unset($_SESSION['show_reject_modal']); ?>
                                    <?php endif; ?>

                                <?php endif; ?>

                                <!-- Use the current page URL as form action -->
                                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="appointmentsForm">
                                    <div class="table-responsive">
                                        <div class="appointments-table">
                                            <!-- Table Header -->
                                            <div class="table-row table-header">
                                                <div class="table-cell"><input type="checkbox" id="select_all" class="form-check-input"></div>
                                                <div class="table-cell">Appointment Time</div>
                                                <div class="table-cell">Student</div>
                                                <div class="table-cell">Counselor</div>
                                                <div class="table-cell">Reason</div>
                                                <div class="table-cell">Status</div>
                                                <div class="table-cell">Actions</div>
                                            </div>

                                            <!-- Table Body -->
                                            <?php if (count($appointments) > 0): ?>
                                                <?php foreach ($appointments as $appointment): ?>
                                                    <div class="table-row">
                                                        <div class="table-cell">
                                                            <input type="checkbox" name="appointments[]" value="<?php echo $appointment['id']; ?>" class="form-check-input appointment-checkbox">
                                                        </div>
                                                        <div class="table-cell"><?php echo htmlspecialchars($appointment['appointment_time']); ?></div>
                                                        <div class="table-cell"><?php echo htmlspecialchars($appointment['student_name']); ?></div>
                                                        <div class="table-cell"><?php echo htmlspecialchars($appointment['counselor_name']); ?></div>
                                                        <div class="table-cell"><?php echo htmlspecialchars($appointment['reason']); ?></div>
                                                        <div class="table-cell">
                                                            <?php
                                                                $statusClass = '';
                                                                if ($appointment['status'] == 'accepted') {
                                                                    $statusClass = 'status-accepted';
                                                                } elseif ($appointment['status'] == 'rejected') {
                                                                    $statusClass = 'status-rejected';
                                                                } elseif ($appointment['status'] == 'pending') {
                                                                    $statusClass = 'status-pending';
                                                                }
                                                            ?>
                                                            <span class="<?php echo $statusClass; ?>">
                                                                <?php echo htmlspecialchars($appointment['status']); ?>
                                                            </span>
                                                        </div>
                                                        <div class="table-cell">
                                                            <?php if ($appointment['status'] == 'pending'): ?>
                                                                <a href="?update_appointment_id=<?php echo $appointment['id']; ?>&status=accepted" class="btn btn-success btn-sm btn-action">Accept</a>
                                                                <button type="button" class="btn btn-danger btn-sm btn-action" data-bs-toggle="modal" data-bs-target="#rejectModal<?php echo $appointment['id']; ?>">Reject</button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="table-row">
                                                    <div class="table-cell" colspan="7" class="text-center">No appointments found</div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <!-- Trash Can Button (delete selected appointments) -->
                                        <button type="button" class="btn delete-btn" id="delete_selected_btn" style="color: #fff;">
                                            <i class="bi bi-trash" style="color: #fff;"></i> Delete Selected
                                        </button>

                                        <!-- Pagination -->
                                        <?php if ($total_pages > 1): ?>
                                        <nav aria-label="Appointments pagination">
                                            <ul class="pagination mb-0">
                                                <!-- Previous page link -->
                                                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                                    <a class="page-link" href="<?php echo ($page <= 1) ? '#' : '?page='.($page-1); ?>" aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>
                                                
                                                <!-- Page numbers -->
                                                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                                    <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                    </li>
                                                <?php endfor; ?>
                                                
                                                <!-- Next page link -->
                                                <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                                    <a class="page-link" href="<?php echo ($page >= $total_pages) ? '#' : '?page='.($page+1); ?>" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
                                        <?php endif; ?>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>

    <!-- Rejection Modals -->
<?php foreach ($appointments as $appointment): ?>
    <?php if ($appointment['status'] == 'pending'): ?>
        <div class="modal fade" id="rejectModal<?php echo $appointment['id']; ?>" tabindex="-1" aria-labelledby="rejectModalLabel<?php echo $appointment['id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel<?php echo $appointment['id']; ?>">Reject Appointment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                            <div class="mb-3">
                                <label for="rejection_reason<?php echo $appointment['id']; ?>" class="form-label">Reason for Rejection</label>
                                <textarea class="form-control" id="rejection_reason<?php echo $appointment['id']; ?>" name="rejection_reason" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="reject_appointment" class="btn btn-danger">Confirm Rejection</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>



<!-- Bootstrap JS and Icon Library -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>

<!-- JavaScript for Select All Checkbox and Delete Confirmation -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select all checkbox functionality
        document.getElementById('select_all').addEventListener('click', function () {
            const checkboxes = document.querySelectorAll('.appointment-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        // Delete button functionality
        document.getElementById('delete_selected_btn').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.appointment-checkbox:checked');
            
            if (checkboxes.length === 0) {
                alert('Please select at least one appointment to delete.');
                return;
            }
            
            if (confirm('Are you sure you want to delete ' + checkboxes.length + ' selected appointment(s)?')) {
                // Add a hidden input to indicate delete operation
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'delete_selected';
                hiddenInput.value = '1';
                document.getElementById('appointmentsForm').appendChild(hiddenInput);
                
                // Submit the form
                document.getElementById('appointmentsForm').submit();
            }
        });
    });
</script>

</body>
</html>