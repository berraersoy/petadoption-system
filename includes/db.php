<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "pet_adoption_db";

// Create the link
$conn = new mysqli($servername, $username, $password, $dbname);

// Show if there is a connection error.
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}


$conn->set_charset("utf8");
?>