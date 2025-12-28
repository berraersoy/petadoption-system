<?php
session_start();
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $pet_id = $_POST['pet_id'];
    $user_id = $_SESSION['user_id'];
    $reason = $_POST['reason'];

   
    $conn->begin_transaction();

    try {
       // Check if the animal is still 'available' and lock the line (FOR UPDATE)
        $check_sql = "SELECT status FROM pets WHERE id = ? FOR UPDATE";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("i", $pet_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row && $row['status'] === 'available') {
            
           // Consume the resource (Set the status to 'pending')
            $update_pet = $conn->prepare("UPDATE pets SET status = 'pending' WHERE id = ?");
            $update_pet->bind_param("i", $pet_id);
            $update_pet->execute();

            // Save the application and the answer.
            $insert_app = $conn->prepare("INSERT INTO adoption_applications (user_id, pet_id, application_text, status) VALUES (?, ?, ?, 'pending')");
            $insert_app->bind_param("iis", $user_id, $pet_id, $reason);
            $insert_app->execute();

            // Commit
            $conn->commit();

            // Redirect to success page
            echo "<h1>Başvuru Başarılı! </h1>";
            echo "<p>Bu hayvan artık sizin için ayrıldı (Pending). Yönetici onayından sonra sahiplenme tamamlanacak.</p>";
            echo "<a href='index.php'>Ana Sayfaya Dön</a>";

        } else {
            // If someone else took it seconds ago
            echo "<h1>Üzgünüz </h1>";
            echo "<p>Bu dostumuz için başvuru az önce başkası tarafından yapıldı.</p>";
            echo "<a href='index.php'>Ana Sayfaya Dön</a>";
        }

    } catch (Exception $e) {
        $conn->rollback(); 
        echo "Bir hata oluştu: " . $e->getMessage();
    }

} else {
    header("Location: index.php");
}
?>