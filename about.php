<?php
session_start();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Hakkımızda - Pet Adoption</title>
    <link rel="icon" href="images/icon.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
       
        .about-header {
            background-color: #8e44ad; 
            color: white;
            padding: 60px 20px;
            text-align: center;
        }
        .about-content {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            line-height: 1.8;
            color: #555;
        }
        .about-content h2 { color: #2c3e50; margin-bottom: 15px; }
        .stats {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
            text-align: center;
        }
        .stat-box { font-size: 1.2rem; font-weight: bold; color: #e67e22; }
    </style>
</head>
<body>

    <div class="navbar">
        <h2> Pet Adoption</h2>
        <div class="nav-links">
            <a href="index.php">Ana Sayfa</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="profile.php">Profilim</a>
                <a href="logout.php" style="color: #ff6b6b;">Çıkış Yap</a>
            <?php else: ?>
                <a href="login.php">Giriş Yap</a>
                <a href="signup.php">Kayıt Ol</a>
            <?php endif; ?>
             <a href="about.php" style="font-weight: bold;">Bizim Hakkımızda</a>
        </div>
    </div>

    <div class="about-header">
        <h1>Minik Dostlarımızı Yuvalarına Kavuşturuyoruz</h1>
        <p>Sevgi paylaştıkça çoğalır.</p>
    </div>

    <div class="about-content">
        <h2>Biz Kimiz?</h2>
        <p>
            Pet Adoption System, 2025 yılında hayvanseverler tarafından, barınaklardaki dostlarımızın sıcak bir yuvaya kavuşması amacıyla kurulmuş kâr amacı gütmeyen bir platformdur. 
            Amacımız, satın alma yerine sahiplenmeyi teşvik etmek ve sokak hayvanlarının yaşam kalitesini artırmaktır.
        </p>
        <br>
        <h2>Misyonumuz</h2>
        <p>
            Her canlının sevgi dolu bir evi hak ettiğine inanıyoruz. Teknolojinin gücünü kullanarak, potansiyel sahiplenicilerle barınakları en hızlı ve güvenilir şekilde bir araya getiriyoruz.
        </p>

        <hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">

        <div class="stats">
            <div class="stat-box">🐶 150+ <br><span style="font-size:0.9rem; color:#777;">Sahiplendirme</span></div>
            <div class="stat-box">🏠 20+ <br><span style="font-size:0.9rem; color:#777;">Barınak</span></div>
            <div class="stat-box">❤️ 1000+ <br><span style="font-size:0.9rem; color:#777;">Mutlu Üye</span></div>
        </div>
    </div>

</body>
</html>