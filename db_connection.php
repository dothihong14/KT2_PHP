<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "mail";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
