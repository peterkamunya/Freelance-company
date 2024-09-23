<?php
include('config.php');

if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    $select = $conn->prepare("SELECT * FROM users WHERE email=?");
    $select->execute([$email]);
    $row = $select->fetch(PDO::FETCH_ASSOC);

    if($select->rowCount() > 0){
        $token = bin2hex(random_bytes(50));
        $expire = date("U") + 1800;

        $insert = $conn->prepare("INSERT INTO password_reset (email, token, expire) VALUES (?, ?, ?)");
        $insert->execute([$email, $token, $expire]);

        $resetLink = "http://yourwebsite.com/resetpassword.php?token=$token";
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password: $resetLink";
        $headers = "From: noreply@yourwebsite.com";

        mail($email, $subject, $message, $headers);
        echo "A password reset link has been sent to your email address.";
    } else {
        echo "No user found with this email address.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<section class="form-container">
    <form action="" method="post" enctype="multipart/form-data">
        <h3>Forgot Password</h3>
        <input type="email" name="email" placeholder="Enter your email" required class="box">
        <input type="submit" name="submit" value="Reset Password" required class="btn">
    </form>
</section>
</body>
</html>
