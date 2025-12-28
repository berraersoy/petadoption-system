<?php
session_start();
include 'includes/db.php';

// Only admins can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$message = "";

// Was the form submitted?
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $description = $_POST['description'];
    
    // --- IMAGE UPLOAD PROCESS ---
    $target_dir = "images/"; 
    $image_name = basename($_FILES["pet_image"]["name"]); 
    
    
    $final_name = time() . "_" . $image_name;
    $target_file = $target_dir . $final_name;
    $uploadOk = 1;

    
    if (!empty($image_name)) {
       
        if (move_uploaded_file($_FILES["pet_image"]["tmp_name"], $target_file)) {
           
        } else {
            $message = "Resim yüklenirken bir hata oluştu.";
            $uploadOk = 0;
        }
    } else {
        $final_name = ""; // Leave blank if no image is selected
    }

    // Save to Database
    if ($uploadOk == 1) {
        
$city = $_POST['city'];


$sql = "INSERT INTO pets (name, breed, age, city, description, image_path, status) VALUES (?, ?, ?, ?, ?, ?, 'available')";
$stmt = $conn->prepare($sql);


$stmt->bind_param("ssisss", $name, $breed, $age, $city, $description, $final_name);
        if ($stmt->execute()) {
            $message = "Yeni dostumuz başarıyla eklendi!";
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
    <title>Yeni Hayvan Ekle</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div style="text-align: center; margin-top: 20px;">
        <a href="admin_dashboard.php" class="btn" style="display:inline-block; background:#555; width:auto; padding: 10px 20px;">← Panele Dön</a>
    </div>

    <form action="" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
        <h2 style="text-align: center; color: #2c3e50;">Yeni Hayvan Ekle</h2>
        
        <?php if($message) echo "<p style='color:green; text-align:center; font-weight:bold;'>$message</p>"; ?>

        <label>İsim:</label>
        <input type="text" name="name" required placeholder="Örn: Boncuk">

        <label>Tür:</label>
        <input type="text" name="breed" required placeholder="Örn: Tekir Kedi">

        <label>Yaş:</label>
        <input type="number" name="age" required placeholder="Örn: 2">

        <label>Şehir:</label>
        <input type="text" name="city" required placeholder="Örn: İzmir">

        <label>Açıklama:</label>
        <textarea name="description" required placeholder="Karakteri nasıl?"></textarea>

        <label>Fotoğraf Yükle:</label>
        <input type="file" name="pet_image" accept="image/*" required>
        
        <button type="submit" style="margin-top: 20px; cursor: pointer;">Listeye Ekle</button>

    </form>