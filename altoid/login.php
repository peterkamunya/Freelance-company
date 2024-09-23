<?php 
include('config.php');

session_start();
// login.php or similar login script


if (isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    $select = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $select->execute([$email, $password]);
    $row = $select->fetch(PDO::FETCH_ASSOC);

    if ($select->rowCount() > 0) {
        if ($row['user_type'] == 'admin') {
            $_SESSION['admin_id'] = $row['id'];
            header('location:adminpanel.php');
        } elseif ($row['user_type'] == 'user') {
            $_SESSION['user_id'] = $row['id'];
            header('location:submit.php'); // Assuming you want to redirect users to the client dashboard
        } else {
            $message[] = "No user found";
        }
    } else {
        $message[] = "Wrong email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-image: url('image/pexels-shvetsa-3986999.jpg'); background-repeat: no-repeat; background-attachment: fixed; background-size: 100% 100%;">

<?php
include("header.php");
?>

<?php  
if (isset($message)) {
    foreach ($message as $msg) {
        echo '<div class="message"><span>' . htmlspecialchars($msg) . '</span></div>';
    }
}
?> 

<section class="form-container">
    <form action="" method="post" enctype="multipart/form-data">
        <h3>Login Now</h3>
        <input type="email" name="email" placeholder="Enter your email" required class="box">
        <input type="password" name="password" placeholder="Enter your password" required class="box">
        <input type="submit" name="submit" value="Login Now" class="btn">
        <p>Don't have an account? <a href="register.php">Register now</a></p>
        <p><a href="forgotpassword.php">Forgot Password?</a></p>
    </form>
</section>
</body>
</html>
