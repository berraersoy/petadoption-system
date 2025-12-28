<?php
include 'includes/db.php';

$message = "";

// Checking if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hashing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result(); 

    if ($check_stmt->num_rows > 0) {
        $message = "Bu e-posta adresi zaten kayıtlı!";
        $check_stmt->close();
    } else {
        $check_stmt->close();

       
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");

        if ($stmt === false) {
            die("Veritabanı hatası: " . $conn->error);
        }

      
        $stmt->bind_param("sss", $username, $email, $hashed_password);

     
        if ($stmt->execute()) {
           // Registration successful, redirect to login page
            header("Location: login.php");
            exit();
        } else {
            $message = "Kayıt sırasında bir hata oluştu: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol - Pet Adoption</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="icon" href="images/icon.png" type="image/png">
    
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #bf8be6; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh;
            margin: 0;
        }
        form { 
            background-color: #fff;
            width: 350px;
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #666; text-align: left; }
        input { 
            width: 100%; 
            padding: 10px; 
            margin-bottom: 15px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            box-sizing: border-box; 
        }
        button { 
            width: 100%; 
            padding: 12px; 
            background: #8e44ad; 
            color: white; 
            border: none; 
            border-radius: 5px;
            cursor: pointer; 
            font-size: 16px;
            font-weight: 600;
            transition: background 0.3s;
        }
        button:hover { background: #732d91; }
        .error { color: #e74c3c; text-align: center; margin-bottom: 15px; }
        p { text-align: center; margin-top: 15px; font-size: 0.9rem; }
        a { color: #8e44ad; text-decoration: none; font-weight: 600; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <form method="POST" action="">
        <h2>Kayıt Ol</h2>
        <?php if($message) echo "<p class='error'>$message</p>"; ?>
        
        <label>Kullanıcı Adı:</label>
        <input type="text" name="username" required>
        
        <label>E-posta:</label>
        <input type="email" name="email" required>
        
        <label>Şifre:</label>
        <input type="password" name="password" required>
        
        <button type="submit">Kayıt Ol</button>
        <p>Zaten üye misin? <a href="login.php">Giriş Yap</a></p>
    </form>

</body>
</html>