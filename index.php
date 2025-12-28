<?php
session_start();
include 'includes/db.php';

// --- FILTERING LOGIC ---
$whereClauses = ["status = 'available'"]; // Default: only available ones

// Keyword Search 
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    // Checks name, breed, and description
    $whereClauses[] = "(name LIKE '%$search%' OR breed LIKE '%$search%' OR description LIKE '%$search%')";
}

// City Filter
if (isset($_GET['city']) && !empty($_GET['city'])) {
    $city = $conn->real_escape_string($_GET['city']);
    $whereClauses[] = "city = '$city'";
}

// Age Filter 
if (isset($_GET['max_age']) && !empty($_GET['max_age'])) {
    $age = (int)$_GET['max_age'];
    $whereClauses[] = "age <= $age";
}

// Build the Query
$sql = "SELECT * FROM pets";
if (count($whereClauses) > 0) {
    $sql .= " WHERE " . implode(' AND ', $whereClauses);
}

// Fetch Results
$result = $conn->query($sql);


$city_sql = "SELECT DISTINCT city FROM pets WHERE status = 'available' ORDER BY city ASC";
$city_result = $conn->query($city_sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Pet Adoption System</title>
    <link rel="icon" href="images/icon.png" type="image/png">
    
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    
    <style>
       
        
        .search-container {
            background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    max-width: 1000px;
    margin: 20px auto;
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    justify-content: center;
    align-items: flex-end;
        }
        .search-group { display: flex; flex-direction: column; }
        .search-group label { display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.9rem; }
        .search-input, .search-select {
            width: 100%;
    height: 50px !important;    
    padding: 0 15px;            
    border: 1px solid #ddd;
    border-radius: 8px;
    font-family: 'Poppins', sans-serif;
    font-size: 1rem;
    color: #333;
    background-color: #fff;
    box-sizing: border-box;    
    margin: 0;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;


    background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23333%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center; 
    background-size: 12px; 
    padding-right: 40px; 
    cursor: pointer;
        }
        .btn-search {
            height: 50px !important; 
    background-color: #8e44ad; 
    color: white;
    border: none;
    padding: 0 30px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    font-size: 1rem;
    display: flex;
    align-items: center;    
    justify-content: center;
    transition: background 0.3s;
        }
        .btn-search:hover { background-color: #732d91; / }
        .btn-clear {
            background-color: #e74c3c; color: white; text-decoration: none; padding: 10px 15px; border-radius: 5px; font-size: 0.9rem; display: flex; align-items: center; height: 45px; box-sizing: border-box;
        }
       
.search-input {
    background-image: none !important;
}
    </style>
</head>
<body> 
    
    <div class="navbar">
        <h2> Pet Adoption</h2>
        <div class="nav-links">
            <?php if(isset($_SESSION['user_id'])): ?>
                <span class="user-info">Hoşgeldin, <?php echo $_SESSION['username']; ?></span>

<a href="add_pet.php" style="background-color: #e67e22; padding: 5px 10px; border-radius: 5px; color: white !important;">+ Sahiplendir</a>

<?php if($_SESSION['role'] == 'admin') echo '<a href="admin_dashboard.php">Yönetim Paneli</a>'; ?>
<a href="profile.php">Profilim</a>
                <a href="logout.php" style="color: #ff6b6b;">Çıkış Yap</a>
            <?php else: ?>
                <a href="login.php">Giriş Yap</a>
                <a href="signup.php">Kayıt Ol</a>
            <?php endif; ?>
            <a href="about.php" style="margin-left: 20px; border-left: 1px solid rgba(255,255,255,0.3); padding-left: 20px;">Bizim Hakkımızda</a>
</div>
        </div>
    </div>

    <div class="search-container">
        <form method="GET" style="display:flex; gap:15px; flex-wrap:wrap; box-shadow:none; padding:0; margin:0; width:auto; max-width:none;">
            
            <div class="search-group">
                <label>Ne arıyorsun?</label>
                <input type="text" name="search" autocomplete="off" class="search-input" placeholder="Golden, British..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            </div>

            <div class="search-group">
                <label>Şehir</label>
                <select name="city" class="search-select">
                    <option value="">Tümü</option>
                    <?php
                    if ($city_result->num_rows > 0) {
                        while($c = $city_result->fetch_assoc()) {
                            $selected = (isset($_GET['city']) && $_GET['city'] == $c['city']) ? 'selected' : '';
                            echo "<option value='" . $c['city'] . "' $selected>" . $c['city'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="search-group">
                <label>Maksimum Yaş</label>
                <input type="number" name="max_age" class="search-input" placeholder="Örn: 2" style="width:100px;" value="<?php echo isset($_GET['max_age']) ? $_GET['max_age'] : ''; ?>">
            </div>

            <div class="search-group">
                <label>&nbsp;</label> <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn-search">Ara / Filtrele</button>
                    <?php if(isset($_GET['search']) || isset($_GET['city'])): ?>
                        <a href="index.php" class="btn-clear">Temizle</a>
                    <?php endif; ?>
                </div>
            </div>

        </form>
    </div>

    <div class="container">
        <?php
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="pet-card">';
                
                // Image Display
                if (!empty($row["image_path"])) {
                    echo '<img src="images/' . $row["image_path"] . '" alt="' . $row["name"] . '" class="pet-img">';
                } else {
                    echo '<div class="pet-icon">🐶</div>';
                }

                echo '<h3>' . $row["name"] . '</h3>';
                echo '<p><strong>Tür:</strong> ' . $row["breed"] . '</p>';
                echo '<p><strong>Yaş:</strong> ' . $row["age"] . '</p>';
                echo '<p style="color:#e67e22; font-size:0.9rem;">📍 ' . $row["city"] . '</p>';
                
                if(isset($_SESSION['user_id'])) {
                    echo '<a href="pet_details.php?id=' . $row["id"] . '" class="btn" style="background-color: #8e44ad;">Sahiplen</a>';
                } else {
                    echo '<a href="login.php" class="btn" style="background-color:#8e44ad;">Sahiplenmek için Giriş Yap</a>';
                }
                
                echo '</div>';
            }
        } else {
            echo "<div style='text-align:center; width:100%; padding:50px;'>
                    <h2> Sonuç Bulunamadı</h2>
                    <p>Aradığınız kriterlere uygun bir dostumuz şu an listede yok.</p>
                    <a href='index.php' class='btn' style='display:inline-block; width:auto; background:#555;'>Tümünü Göster</a>
                  </div>";
        }
        ?>
    </div>

</body>
</html>