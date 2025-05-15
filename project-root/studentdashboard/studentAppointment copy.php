<?php
session_start();
include('../includes/db.php');

// Fetch counselor data
$counselorQuery = $pdo->query("SELECT * FROM counselors WHERE active = 1");
$counselors = $counselorQuery->fetchAll(PDO::FETCH_ASSOC);

// Handle appointment booking
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_appointment'])) {
    $counselor_id = $_POST['counselor_id'];
    $appointment_time = $_POST['appointment_time'];
    $user_id = 1; // Assuming user ID is available
    $reason = $_POST['reason'];
    $status = 'pending'; // Set the initial status as "pending"
    $notes = 'Scheduled appointment'; // Move status to notes

    // Insert appointment
    $stmt = $pdo->prepare("INSERT INTO appointments (user_id, counselor_id, appointment_time, reason, status, notes) 
                           VALUES (:user_id, :counselor_id, :appointment_time, :reason, :status, :notes)");
    if ($stmt->execute([
        ':user_id' => $user_id,
        ':counselor_id' => $counselor_id,
        ':appointment_time' => $appointment_time,
        ':reason' => $reason,
        ':status' => $status,
        ':notes' => $notes
    ])) {
        $_SESSION['message'] = "Appointment booked successfully!";
        $_SESSION['message_type'] = "success"; // Success toast
    } else {
        $_SESSION['message'] = "Error booking appointment.";
        $_SESSION['message_type'] = "danger"; // Error toast
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle multiple appointment deletion
if (isset($_POST['delete_selected'])) {
    if (isset($_POST['appointments']) && is_array($_POST['appointments'])) {
        $appointment_ids = implode(",", $_POST['appointments']);
        $deleteQuery = "DELETE FROM appointments WHERE id IN ($appointment_ids)";
        $pdo->exec($deleteQuery);
        $_SESSION['message'] = "Appointments deleted successfully!";
        $_SESSION['message_type'] = "success"; // Success toast
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Fetch existing appointments
$appointmentQuery = $pdo->query("SELECT a.id, a.appointment_time, c.name AS counselor_name, a.status, a.notes 
                                 FROM appointments a 
                                 JOIN counselors c ON a.counselor_id = c.id 
                                 WHERE a.user_id = 1"); // Assuming user ID = 1
$appointments = $appointmentQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4 px-4" style="margin-left: 220px; max-width: calc(100% - 220px);">
    <div class="row gx-4 mt-4">
        <!-- Appointment Booking -->
        <section class="col-12 col-md-6 col-lg-3 mb-4 mb-md-0">
            <div class="card shadow-sm" style="height: 100%;">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Book an Appointment</h2>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="counselor_id" class="form-label">Choose a Counselor</label>
                            <select name="counselor_id" id="counselor_id" class="form-select" required>
                                <?php foreach ($counselors as $counselor): ?>
                                <option value="<?php echo $counselor['id']; ?>">
                                    <?php echo htmlspecialchars($counselor['name']); ?> - <?php echo htmlspecialchars($counselor['specialization']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="appointment_time" class="form-label">Choose Date and Time</label>
                            <input type="text" id="appointment_time" name="appointment_time" class="form-control" required placeholder="Select date and time">
                        </div>
                        <!-- Add Flatpickr CSS and JS -->
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
                        <style>
                            .flatpickr-calendar {
                                font-size: 14px;
                                width: 280px;
                                position: fixed !important;
                                top: 50% !important;
                                left: 50% !important;
                                transform: translate(-50%, -50%) !important;
                                z-index: 9999 !important;
                            }
                            .flatpickr-current-month {
                                font-size: 14px;
                            }
                            .flatpickr-day {
                                height: 32px;
                                line-height: 32px;
                            }
                        </style>
                        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                        <script>
                            flatpickr("#appointment_time", {
                                enableTime: true,
                                dateFormat: "Y-m-d H:i",
                                minDate: "today",
                                minTime: "09:00",
                                maxTime: "17:00",
                                disable: [
                                    function(date) {
                                        return (date.getDay() === 0 || date.getDay() === 6);
                                    }
                                ],
                                time_24hr: true,
                                static: true
                            });
                        </script>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for Appointment</label>
                            <textarea name="reason" id="reason" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" name="book_appointment" class="btn btn-primary w-100">Book Appointment</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Your Appointments -->
        <section class="col-12 col-md-6 col-lg-9">
            <div class="card shadow-sm" style="height: 100%;">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Your Appointments</h2>
                    <form method="POST">
                        <div class="table-responsive" style="max-height: 500px;">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select_all" class="form-check-input"></th>
                                        <th>Appointment Time</th>
                                        <th>Counselor</th>
                                        <th>Status</th>
                                        <th>Notes</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($appointments as $appointment): ?>
                                    <tr>
                                        <td><input type="checkbox" name="appointments[]" value="<?php echo $appointment['id']; ?>" class="form-check-input"></td>
                                        <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['counselor_name']); ?></td>
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
                                            <?php if ($appointment['status'] == 'pending'): ?>
                                            <a href="?cancel_appointment_id=<?php echo $appointment['id']; ?>" class="btn btn-danger btn-sm">Cancel</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Trash Can Button (delete selected appointments) -->
                        <button type="submit" name="delete_selected" class="btn btn-danger" id="delete_selected_btn">
                            <i class="bi bi-trash"></i> Delete Selected
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Toast Notification -->
<?php if (isset($_SESSION['message'])): ?>
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
    <div id="toastMessage" class="toast show align-items-center text-white bg-<?php echo $_SESSION['message_type']; ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <?php echo htmlspecialchars($_SESSION['message']); ?>
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript for Select All Checkbox -->
<script>
    document.getElementById('select_all').addEventListener('click', function () {
        const checkboxes = document.querySelectorAll('input[name="appointments[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
</script>
</body>
</html>
