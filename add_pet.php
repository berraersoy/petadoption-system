<?php
session_start();
include 'includes/db.php';

// Cannot be seen by those who are not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $city = $_POST['city'];
    $description = $_POST['description'];
    $owner_id = $_SESSION['user_id']; // The ID of the user who posted the ad
    
    // --- IMAGE UPLOAD ---
    $target_dir = "images/";
    $image_name = basename($_FILES["pet_image"]["name"]);
    $final_name = time() . "_" . $image_name;
    $target_file = $target_dir . $final_name;
    $uploadOk = 1;

    if (!empty($image_name)) {
        if (!move_uploaded_file($_FILES["pet_image"]["tmp_name"], $target_file)) {
             $message = "Resim yüklenemedi.";
             $uploadOk = 0;
        }
    } else {
        $final_name = ""; 
    }

    if ($uploadOk == 1) {
        
        $sql = "INSERT INTO pets (name, breed, age, city, description, image_path, status, owner_id) VALUES (?, ?, ?, ?, ?, ?, 'available', ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisssi", $name, $breed, $age, $city, $description, $final_name, $owner_id);

        if ($stmt->execute()) {
            
            echo "<script>alert('İlanınız başarıyla eklendi! 🐶'); window.location.href='index.php';</script>";
            exit();
        } else {
            $message = "Veritabanı hatası: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sahiplendir - İlan Ver</title>
    <link rel="icon" href="images/icon.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .form-container { max-width: 500px; margin: 40px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 20px; }
    </style>
</head>
<body>

    <div class="navbar">
        <h2> Pet Adoption</h2>
        <div class="nav-links">
            <a href="index.php">← Ana Sayfaya Dön</a>
        </div>
    </div>

    <div class="form-container">
        <h2> Sahiplendir</h2>
        <?php if($message) echo "<p style='color:red; text-align:center;'>$message</p>"; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <label>Hayvanın Adı:</label>
            <input type="text" name="name" required placeholder="Örn: Pamuk">

            <label>Türü / Cinsi:</label>
            <input type="text" name="breed" required placeholder="Örn: Tekir Kedi">

            <label>Yaşı:</label>
            <input type="number" name="age" required placeholder="Örn: 1">

            <label>Şehir:</label>
            <input type="text" name="city" required placeholder="Örn: İstanbul">

            <label>Açıklama:</label>
            <textarea name="description" required placeholder="Huyu suyu nasıldır?"></textarea>

            <label>Fotoğraf:</label>
            <input type="file" name="pet_image" accept="image/*" required>

            <button type="submit" style="margin-top:20px;">İlanı Yayınla</button>
        </form>
    </div>

</body>
</html>