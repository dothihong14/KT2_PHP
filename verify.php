<?php
require 'db_connection.php'; // Đảm bảo file này chứa thông tin kết nối DB

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Kiểm tra token trong cơ sở dữ liệu
    $stmt = $conn->prepare("SELECT * FROM users WHERE token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Kích hoạt tài khoản
        $stmt = $conn->prepare("UPDATE users SET token = NULL WHERE token = :token");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        echo "Tài khoản của bạn đã được xác nhận!";
    } else {
        echo "Token không hợp lệ hoặc đã hết hạn.";
    }
} else {
    echo "Không có token!";
}
?>
