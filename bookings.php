<?php
session_start();
include 'config.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['email'];

// Updated query to match your Figma fields
$stmt = $conn->prepare("SELECT id, workspace_name, booking_date, start_time, end_time, location, purpose, status FROM bookings WHERE user_email = ? ORDER BY booking_date DESC");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings | Virtual Workplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <style>
        :root { --uph-maroon: #7b0d0d; }
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        
        .navbar { background: white !important; border-bottom: 1px solid #dee2e6; }
        .nav-link.active { color: var(--uph-maroon) !important; font-weight: 600; border-bottom: 2px solid var(--uph-maroon); }
        
        /* Figma-inspired Card Styling */
        .booking-card { 
            border: none; 
            border-radius: 12px; 
            background: white;
            transition: box-shadow 0.2s;
        }
        .booking-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        
        .status-badge { font-size: 0.75rem; text-transform: capitalize; padding: 4px 10px; }
        .info-row { display: flex; align-items: center; gap: 10px; font-size: 0.9rem; color: #6c757d; margin-bottom: 8px; }
        .info-row i { width: 16px; text-align: center; }
        
        .btn-cancel-icon { color: #dc3545; background: transparent; border: none; padding: 5px 10px; border-radius: 6px; }
        .btn-cancel-icon:hover { background: #fff5f5; color: #a71d2a; }
        
        .empty-state { padding: 60px; text-align: center; background: white; border-radius: 15px; border: 1px dashed #dee2e6; }
        .empty-state i { font-size: 48px; color: #ced4da; margin-bottom: 15px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">Virtual Workplace</a>
        <div class="ms-auto">
             <a href="logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
        </div>
    </div>
</nav>

<main class="container py-5">
    <div class="mb-5">
        <h2 class="fw-bold">Welcome back, <?php echo explode('@', $_SESSION['email'])[0]; ?>!</h2>
        <p class="text-muted">Book your virtual workspace and manage your appointments</p>
    </div>

    <ul class="nav nav-tabs mb-4 border-0">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Available Workspaces</a></li>
        <li class="nav-item"><a class="nav-link active" href="bookings.php">My Bookings</a></li>
    </ul>

    <?php if ($result->num_rows === 0): ?>
        <div class="empty-state">
            <i class="fa fa-calendar"></i>
            <p class="mb-1 fw-bold text-dark">No bookings yet</p>
            <p class="text-muted small">Book a workspace to get started</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-12 col-lg-6">
                    <div class="card booking-card shadow-sm p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($row['workspace_name']); ?></h5>
                                <?php 
                                    $statusClass = 'bg-primary'; // default/confirmed
                                    if($row['status'] == 'pending') $statusClass = 'bg-secondary';
                                    if($row['status'] == 'cancelled') $statusClass = 'bg-danger';
                                ?>
                                <span class="badge <?php echo $statusClass; ?> status-badge rounded-pill">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </div>
                            <form action="cancel_booking.php" method="POST" onsubmit="return confirm('Cancel this booking?');">
                                <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn-cancel-icon" title="Cancel Booking">
                                    <i class="fa fa-times"></i>
                                </button>
                            </form>
                        </div>

                        <div class="space-y-2">
                            <div class="info-row">
                                <i class="fa fa-calendar-o"></i>
                                <span><?php echo date('M d, Y', strtotime($row['booking_date'])); ?></span>
                            </div>
                            <div class="info-row">
                                <i class="fa fa-clock-o"></i>
                                <span><?php echo $row['start_time'] . ' - ' . $row['end_time']; ?></span>
                            </div>
                            <div class="info-row">
                                <i class="fa fa-map-marker"></i>
                                <span><?php echo htmlspecialchars($row['location']); ?></span>
                            </div>
                        </div>

                        <?php if (!empty($row['purpose'])): ?>
                            <div class="mt-3 pt-3 border-top">
                                <p class="small text-muted mb-0">
                                    <span class="fw-bold text-dark">Purpose: </span>
                                    <?php echo htmlspecialchars($row['purpose']); ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</main>

</body>
</html>