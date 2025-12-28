<?php
session_start();
include 'includes/db.php';

// If you are not logged in, you will be redirected to the login page.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get ID from URL
if (isset($_GET['id'])) {
    $pet_id = $_GET['id'];
    
   
    $sql = "SELECT * FROM pets WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pet_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pet = $result->fetch_assoc();

    // If the animal cannot be found or is adopted by someone else
    if (!$pet || $pet['status'] != 'available') {
        echo "<h1>Üzgünüz, bu dostumuz artık sahiplenilmeye uygun değil.</h1>";
        echo "<a href='index.php'>Ana Sayfaya Dön</a>";
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <title><?php echo $pet['name']; ?> - Sahiplen</title>
    <link rel="icon" href="images/icon.png" type="image/png">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { font-family: sans-serif; padding: 20px; display: flex; justify-content: center; }
        .detail-card { border: 1px solid #ccc; padding: 30px; border-radius: 10px; width: 500px; background: white; }
        textarea { width: 100%; height: 100px; margin-top: 10px; padding: 10px; }
        .btn-adopt { background-color: #28a745; color: white; padding: 15px; width: 100%; border: none; font-size: 16px; cursor: pointer; margin-top: 15px; }
    </style>
</head>
<body>

    <div class="detail-card">
        <h1><?php 
if (!empty($pet["image_path"])) {
    echo '<img src="images/' . $pet["image_path"] . '" alt="' . $pet["name"] . '" class="detail-img">';
} else {
    echo '<div style="font-size:80px; text-align:center;">🐶</div>';
}
?> <?php echo $pet['name']; ?></h1>
        <p><strong>Tür:</strong> <?php echo $pet['breed']; ?></p>
        <p><strong>Yaş:</strong> <?php echo $pet['age']; ?></p>
        <p><strong>Konum:</strong> 📍 <?php echo $pet['city']; ?></p>
        <p><strong>Hakkında:</strong> <?php echo $pet['description']; ?></p>
       

        <hr>
        
        <h3>Sahiplenme Başvurusu</h3>
        <p>Neden <?php echo $pet['name']; ?> için en iyi yuva sizsiniz?</p>
        
        <form action="process_adoption.php" method="POST">
            <input type="hidden" name="pet_id" value="<?php echo $pet['id']; ?>">
            
            <label>Cevabınız :</label>
            <textarea name="reason" required placeholder="Lütfen ev ortamınızı ve tecrübenizi anlatın..."></textarea>
            
            <button type="submit" class="btn-adopt">Başvuruyu Tamamla</button>
        </form>
        
        <br>
        <a href="index.php">Listeye Geri Dön</a>
    </div>

</body>
</html>