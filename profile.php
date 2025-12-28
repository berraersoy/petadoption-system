<?php
session_start();
include 'includes/db.php';

// If not logged in, redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// --- PROFILE UPDATE PROCESS ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST['username'];
    $new_password = $_POST['password'];

  // If the password box is full, also update the password.
    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $new_username, $hashed_password, $user_id);
    } else {
        // If the password is blank, only update the username.
        $sql = "UPDATE users SET username = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_username, $user_id);
    }

    if ($stmt->execute()) {
        $message = "Profil başarıyla güncellendi!";
        $_SESSION['username'] = $new_username; 
    } else {
        $message = "Hata oluştu.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <title>Profilim</title>
    <link rel="icon" href="images/icon.png" type="image/png">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .container { display: flex; gap: 50px; max-width: 1000px; margin: 0 auto; }
        .section { flex: 1; background: white; padding: 20px; border-radius: 8px; border: 1px solid #ddd; }
        
      
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #eee; padding: 10px; text-align: left; }
        th { background-color: #333; color: white; }
        
        
        input { width: 90%; padding: 10px; margin: 10px 0; display: block; }
        button { background-color: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .msg { color: green; font-weight: bold; }
    </style>
</head>
<body>

    <div style="margin-bottom: 20px;">
        <a href="index.php">← Ana Sayfaya Dön</a>
    </div>

    <h1> Kullanıcı Paneli</h1>

    <div class="container">
        
        <div class="section">
            <h3>Bilgilerimi Güncelle</h3>
            <?php if($message) echo "<p class='msg'>$message</p>"; ?>
            
            <form method="POST" action="">
                <label>Kullanıcı Adı:</label>
                <input type="text" name="username" value="<?php echo $_SESSION['username']; ?>" required>
                
                <label>Yeni Şifre (Değiştirmek istemiyorsanız boş bırakın):</label>
                <input type="password" name="password" placeholder="Yeni şifre...">
                
                <button type="submit">Güncelle</button>
            </form>
        </div>

        <div class="section">
            <h3> Başvuru Geçmişim</h3>
            <table>
                <thead>
                    <tr>
                        <th>Hayvan</th>
                        <th>Durum</th>
                        <th>Tarih</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // User applications
                    $sql = "SELECT p.name, a.status, a.application_date 
                            FROM adoption_applications a 
                            JOIN pets p ON a.pet_id = p.id 
                            WHERE a.user_id = ?";
                    
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                          
                            $color = 'black';
                            if($row['status'] == 'approved') $color = 'green';
                            if($row['status'] == 'rejected') $color = 'red';
                            if($row['status'] == 'pending') $color = 'orange';

                            echo "<tr>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td style='color:$color; font-weight:bold;'>" . strtoupper($row['status']) . "</td>";
                            echo "<td>" . $row['application_date'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Henüz bir başvurunuz yok.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>