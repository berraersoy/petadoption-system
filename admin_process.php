<?php
session_start();
include 'includes/db.php';

// Only users with admin privileges can perform operations
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    die("Yetkisiz erişim.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $app_id = $_POST['app_id'];
    $action = $_POST['action'];

    // First, find which pet the application belongs to
    $find_sql = "SELECT pet_id FROM adoption_applications WHERE id = ?";
    $stmt = $conn->prepare($find_sql);
    $stmt->bind_param("i", $app_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $pet_id = $row['pet_id'];

    if ($action == 'approve') {
        // Set the application status to 'approved'
        $update_app = $conn->prepare("UPDATE adoption_applications SET status = 'approved' WHERE id = ?");
        $update_app->bind_param("i", $app_id);
        $update_app->execute();

        // Set the pet status to 'adopted' (No longer available)
        $update_pet = $conn->prepare("UPDATE pets SET status = 'adopted' WHERE id = ?");
        $update_pet->bind_param("i", $pet_id);
        $update_pet->execute();

    } elseif ($action == 'reject') {
        // Set the application status to 'rejected'
        $update_app = $conn->prepare("UPDATE adoption_applications SET status = 'rejected' WHERE id = ?");
        $update_app->bind_param("i", $app_id);
        $update_app->execute();

        // Set the pet status back to 'available' (Resource Release)
        $update_pet = $conn->prepare("UPDATE pets SET status = 'available' WHERE id = ?");
        $update_pet->bind_param("i", $pet_id);
        $update_pet->execute();
    }

    // Redirect back to the dashboard after the operation
    header("Location: admin_dashboard.php");
    exit();
}
?>