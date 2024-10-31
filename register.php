<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Kết nối cơ sở dữ liệu
require 'db_connection.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(16)); // Tạo token ngẫu nhiên

    // Kiểm tra email đã tồn tại
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "Email đã tồn tại! Vui lòng chọn email khác.";
    } else {
        // Lưu thông tin người dùng vào cơ sở dữ liệu
        $stmt = $conn->prepare("INSERT INTO users (email, password, token) VALUES (:email, :password, :token)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':token', $token);
        
        if ($stmt->execute()) {
            // Gửi email xác nhận
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; 
                $mail->SMTPAuth = true;
                $mail->Username = 'linhhtt.bws.gmail.com'; 
                $mail->Password = 'SOSquadi'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('info@example.com', 'Nhom6');
                $mail->addAddress($email); 

                $mail->isHTML(true);
                $mail->Subject = 'Xác nhận đăng ký tài khoản';
                $mail->Body = "Click vào liên kết sau để xác nhận tài khoản của bạn: 
                <a href='http://yourdomain.com/verify.php?token=$token'>Xác nhận tài khoản</a>";

                $mail->send();
                echo "Email xác nhận đã được gửi!";
            } catch (Exception $e) {
                echo "Không thể gửi email. Lỗi: {$mail->ErrorInfo}";
            }
        } else {
            echo "Lỗi: Không thể đăng ký!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng ký tài khoản</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f8ff;
      margin: 0;
      padding: 20px;
    }
    .container {
      max-width: 400px;
      margin: auto;
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
      text-align: center;
    }
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    input[type="submit"] {
      background-color: #004aad;
      color: white;
      border: none;
      padding: 10px;
      cursor: pointer;
      border-radius: 4px;
    }
    input[type="submit"]:hover {
      background-color: #003b8a;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Đăng ký tài khoản</h2>
    <form method="POST" action="">
      <input type="email" name="email" placeholder="Nhập email" required>
      <input type="password" name="password" placeholder="Nhập mật khẩu" required>
      <input type="submit" value="Đăng ký">
    </form>
  </div>
</body>
</html>
