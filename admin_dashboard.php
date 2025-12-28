<?php
// Debugging mode
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'includes/db.php';

// Only admins can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo "Bu sayfaya erişim yetkiniz yok.";
    echo "<br><a href='index.php'>Ana Sayfaya Dön</a>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yönetici Paneli</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="icon" href="images/icon.png" type="image/png">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { font-family: 'Poppins', sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: purple; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        
        .btn-approve { background-color: #27ae60; color: white; padding: 6px 12px; border-radius: 4px; border:none; cursor:pointer; font-weight:bold; }
        .btn-reject { background-color: #e74c3c; color: white; padding: 6px 12px; border-radius: 4px; border:none; cursor:pointer; font-weight:bold; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { text-decoration: none; color: white; border-radius: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Yönetici Paneli</h1>
        <div>
            <a href="admin_add_pet.php" class="btn" style="background-color:purple; padding: 10px 20px;">+ Yeni Hayvan Ekle</a>
            <a href="index.php" class="btn" style="background-color:#7f8c8d; padding: 10px 20px;">Siteye Dön</a>
        </div>
    </div>

    <h2>Bekleyen Sahiplenme Başvuruları</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Hayvan Adı</th>
                <th>Başvuran Kişi</th>
                <th>Başvuru Nedeni</th>
                <th>Tarih</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // SQL Query
            $sql = "SELECT a.id as app_id, u.username, p.name as pet_name, a.application_text, a.application_date 
                    FROM adoption_applications a
                    JOIN users u ON a.user_id = u.id
                    JOIN pets p ON a.pet_id = p.id
                    WHERE a.status = 'pending'";
            
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    
            ?>
                    <tr>
                        <td><?php echo $row['app_id']; ?></td>
                        <td><?php echo $row['pet_name']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><i>"<?php echo $row['application_text']; ?>"</i></td>
                        <td><?php echo $row['application_date']; ?></td>
                        <td>
                        <form action="admin_process.php" method="POST" style="display: flex; gap: 10px; justify-content: flex-start;">
                                <input type="hidden" name="app_id" value="<?php echo $row['app_id']; ?>">
                                
                                <button type="submit" name="action" value="approve" class="btn-approve">Onayla</button>
                                
                                <button type="submit" name="action" value="reject" class="btn-reject" onclick="return confirmAction('Bu başvuruyu reddetmek istediğinize emin misiniz?');">Reddet</button>
                            </form>
                        </td>
                    </tr>
            <?php
                    
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>Şu an bekleyen başvuru yok.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script src="js/script.js"></script>

</body>
</html>