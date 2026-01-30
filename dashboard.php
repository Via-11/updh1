<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$workspaces = [
    ['name' => 'Executive Office', 'desc' => 'Premium private office with high-speed internet and professional setup.', 'price' => 50, 'cap' => '1-2', 'loc' => 'Virtual Zone A', 'status' => 'Available', 'icon' => 'building'],
    ['name' => 'Team Meeting Room', 'desc' => 'Collaborative space with interactive whiteboard and breakout capabilities.', 'price' => 75, 'cap' => '5-10', 'loc' => 'Virtual Zone B', 'status' => 'Available', 'icon' => 'users'],
    ['name' => 'Conference Hall', 'desc' => 'Large virtual space for presentations, webinars, and company-wide meetings.', 'price' => 120, 'cap' => '20-50', 'loc' => 'Virtual Zone C', 'status' => 'Available', 'icon' => 'microphone'],
    ['name' => 'Private Workspace', 'desc' => 'Quiet individual workspace ideal for focused work and concentration.', 'price' => 35, 'cap' => '1', 'loc' => 'Virtual Zone D', 'status' => 'Booked', 'icon' => 'briefcase'],
    ['name' => 'Creative Studio', 'desc' => 'Open workspace designed for brainstorming, design work, and collaboration.', 'price' => 65, 'cap' => '3-6', 'loc' => 'Virtual Zone E', 'status' => 'Available', 'icon' => 'paint-brush'],
    ['name' => 'Training Room', 'desc' => 'Virtual training facility with recording capabilities and learning tools.', 'price' => 90, 'cap' => '10-20', 'loc' => 'Virtual Zone F', 'status' => 'Available', 'icon' => 'graduation-cap'],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virtual Workplace | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <style>
        :root { --uph-maroon: #7b0d0d; }
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        
        /* Header Styling */
        .navbar { background: white !important; border-bottom: 1px solid #dee2e6; }
        .nav-link.active { color: var(--uph-maroon) !important; font-weight: 600; border-bottom: 2px solid var(--uph-maroon); }
        
        /* Card Styling */
        .workspace-card { 
            border: none; 
            border-radius: 15px; 
            transition: transform 0.2s, box-shadow 0.2s; 
            height: 100%;
        }
        .workspace-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .icon-box { 
            width: 50px; height: 50px; 
            background: #fdf2f2; 
            color: var(--uph-maroon); 
            border-radius: 10px; 
            display: flex; align-items: center; justify-content: center; font-size: 24px;
        }
        .badge-available { background-color: #d1e7dd; color: #0f5132; }
        .badge-booked { background-color: #f8d7da; color: #842029; }
        .btn-book { background-color: #111; color: white; border-radius: 8px; font-weight: 500; border: none; padding: 10px; }
        .btn-book:hover { background-color: #333; color: white; }
        .btn-booked { background-color: #6c757d; color: white; cursor: not-allowed; border-radius: 8px; border: none; padding: 10px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <i class="fa fa-building text-primary me-2"></i>
            <div>
                <span class="fw-bold d-block" style="line-height: 1;">Virtual Workplace</span>
                <small class="text-muted" style="font-size: 10px;">UPH Las Piñas Campus</small>
            </div>
        </a>
        <div class="ms-auto d-flex align-items-center">
            <i class="fa fa-bell-o me-4 text-muted"></i>
            <div class="d-flex align-items-center border-start ps-4">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                    <?php echo strtoupper(substr($_SESSION['email'], 0, 1)); ?>
                </div>
                <div class="d-none d-md-block me-3">
                    <p class="mb-0 fw-bold small"><?php echo explode('@', $_SESSION['email'])[0]; ?></p>
                    <p class="mb-0 text-muted" style="font-size: 11px;">Student/User</p>
                </div>
                <a href="logout.php" class="btn btn-sm btn-outline-danger"><i class="fa fa-sign-out"></i></a>
            </div>
        </div>
    </div>
</nav>

<main class="container py-5">
    <div class="mb-5">
        <h2 class="fw-bold">Welcome back, <?php echo explode('@', $_SESSION['email'])[0]; ?>!</h2>
        <p class="text-muted">Book your virtual workspace and manage your appointments</p>
    </div>

    <ul class="nav nav-tabs mb-4 border-0">
        <li class="nav-item">
            <a class="nav-link active" href="dashboard.php">Available Workspaces</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-muted" href="bookings.php">My Bookings</a>
        </li>
    </ul>

    <div class="row g-4">
        <?php foreach ($workspaces as $ws): ?>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card workspace-card shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="icon-box">
                        <i class="fa fa-<?php echo $ws['icon']; ?>"></i>
                    </div>
                    <span class="badge <?php echo $ws['status'] == 'Available' ? 'badge-available' : 'badge-booked'; ?> rounded-pill">
                        <?php echo $ws['status']; ?>
                    </span>
                </div>
                
                <h5 class="fw-bold"><?php echo $ws['name']; ?></h5>
                <p class="text-muted small mb-3"><?php echo $ws['desc']; ?></p>
                
                <div class="row g-2 mb-4 text-muted small">
                    <div class="col-6"><i class="fa fa-money me-1"></i> ₱<?php echo $ws['price']; ?>/hour</div>
                    <div class="col-6"><i class="fa fa-users me-1"></i> Capacity: <?php echo $ws['cap']; ?></div>
                    <div class="col-12"><i class="fa fa-map-marker me-1"></i> <?php echo $ws['loc']; ?></div>
                </div>

                <?php if($ws['status'] == 'Available'): ?>
                    <form action="process_booking.php" method="POST">
                        <input type="hidden" name="workspace_name" value="<?php echo htmlspecialchars($ws['name']); ?>">
                        <input type="hidden" name="location" value="<?php echo htmlspecialchars($ws['loc']); ?>">
                        <button type="submit" class="btn btn-book w-100">Book Workspace</button>
                    </form>
                <?php else: ?>
                    <button class="btn btn-booked w-100" disabled>Not Available</button>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>