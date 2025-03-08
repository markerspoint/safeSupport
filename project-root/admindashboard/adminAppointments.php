<?php
session_start();
include('../includes/db.php');
include('../admindashboard/adminHeader.php');

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

// Fetch appointments
$appointmentQuery = $pdo->query("SELECT a.id, a.appointment_time, a.reason, c.name AS counselor_name, u.name AS student_name, a.status, a.notes 
                                 FROM appointments a 
                                 JOIN counselors c ON a.counselor_id = c.id
                                 JOIN users u ON a.user_id = u.id");
$appointments = $appointmentQuery->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container mt-4 px-4" style="margin-left: 220px; max-width: calc(100% - 220px);">
    <div class="row">
        <section class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Manage Appointments</h2>
                    
                    <!-- Success/Error Toast Message -->
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
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
                    <?php endif; ?>

                    <!-- Use the current page URL as form action -->
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="appointmentsForm">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select_all" class="form-check-input"></th>
                                        <th>Appointment Time</th>
                                        <th>Student</th>
                                        <th>Counselor</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Notes</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($appointments as $appointment): ?>
                                    <tr>
                                        <td><input type="checkbox" name="appointments[]" value="<?php echo $appointment['id']; ?>" class="form-check-input appointment-checkbox"></td>
                                        <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['student_name']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['counselor_name']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['reason']); ?></td>
                                        <td>
                                            <?php
                                                $statusClass = '';
                                                if ($appointment['status'] == 'accepted') {
                                                    $statusClass = 'text-success';
                                                } elseif ($appointment['status'] == 'rejected') {
                                                    $statusClass = 'text-danger';
                                                } elseif ($appointment['status'] == 'pending') {
                                                    $statusClass = 'text-warning';
                                                }
                                            ?>
                                            <span class="<?php echo $statusClass; ?>">
                                                <?php echo htmlspecialchars($appointment['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($appointment['notes']); ?></td>
                                        <td>
                                            <!-- Accept Button: Only show if the status is pending -->
                                            <?php if ($appointment['status'] == 'pending'): ?>
                                                <a href="?update_appointment_id=<?php echo $appointment['id']; ?>&status=accepted" class="btn btn-success btn-sm">Accept</a>
                                            <?php endif; ?>

                                            <!-- Reject Button: Only show if the status is pending -->
                                            <?php if ($appointment['status'] == 'pending'): ?>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal<?php echo $appointment['id']; ?>">Reject</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <!-- Modal for Rejection Note -->
                                    <div class="modal fade" id="rejectModal<?php echo $appointment['id']; ?>" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="rejectModalLabel">Reject Appointment</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form method="POST" action="?update_appointment_id=<?php echo $appointment['id']; ?>&status=rejected">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="rejection_note" class="form-label">Rejection Note</label>
                                                            <textarea name="rejection_note" id="rejection_note" class="form-control" rows="3" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-danger">Reject Appointment</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Trash Can Button (delete selected appointments) -->
                        <button type="button" class="btn btn-danger" id="delete_selected_btn">
                            <i class="bi bi-trash"></i> Delete Selected
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

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