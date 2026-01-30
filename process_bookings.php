<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $workspace = $_POST['workspace_name'];
    $location = $_POST['location'];
    
    // Set defaults to match your Figma requirements
    $status = 'confirmed';
    $date = date('Y-m-d');
    $start = "09:00 AM"; 
    $end = "10:00 AM";
    $purpose = "General Work";

    $stmt = $conn->prepare("INSERT INTO bookings (user_email, workspace_name, location, status, booking_date, start_time, end_time, purpose) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $email, $workspace, $location, $status, $date, $start, $end, $purpose);

    if ($stmt->execute()) {
        header("Location: bookings.php"); // Redirect to your bookings page
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
}
?>