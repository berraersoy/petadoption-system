<?php
session_start(); 
include 'includes/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // --- SECURE LOGIN PROCESS (SQL INJECTION PROTECTED) ---

    
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    
    if ($stmt === false) {
        die("Sorgu hatası: " . $conn->error); // Prints errors if any
    }

    
    $stmt->bind_param("s", $email);

    
    $stmt->execute();

    
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        
        $row = $result->fetch_assoc();

           // 5. Check Password (Hash Verification)

        if (password_verify($password, $row['password'])) {
            // --- LOGIN SUCCESSFUL ---
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role']; 
            
            header("Location: index.php"); 
            exit();
        } else {
            //Password is incorrect
            $message = "Hatalı şifre! ";
        }
    } else {
        //No user
        $message = "Bu e-posta adresiyle kayıtlı kullanıcı bulunamadı.";
    }

  
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap - Pet Adoption</title>
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
        label { display: block; margin-bottom: 5px; color: #666; }
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
        .error { 
            color: #e74c3c; 
            text-align: center; 
            margin-bottom: 15px; 
            font-size: 0.9rem;
        }
        p { text-align: center; margin-top: 15px; font-size: 0.9rem; }
        a { color: #8e44ad; text-decoration: none; font-weight: 600; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <form method="POST" action="">
        <h2>Giriş Yap</h2>
        <?php if($message) echo "<p class='error'>$message</p>"; ?>
        
        <label>E-posta:</label>
        <input type="email" name="email" required>
        
        <label>Şifre:</label>
        <input type="password" name="password" required>
        
        <button type="submit">Giriş Yap</button>
        <p>Hesabın yok mu? <a href="signup.php">Kayıt Ol</a></p>
    </form>

</body>
</html>