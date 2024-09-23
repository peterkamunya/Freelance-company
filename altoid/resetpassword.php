<?php
include('config.php');

if(isset($_GET['token'])){
    $token = $_GET['token'];
    $token = filter_var($token, FILTER_SANITIZE_STRING);

    $select = $conn->prepare("SELECT * FROM password_reset WHERE token=? AND expire >= ?");
    $select->execute([$token, date("U")]);
    $row = $select->fetch(PDO::FETCH_ASSOC);

    if($select->rowCount() > 0){
        $email = $row['email'];
    } else {
        echo "Invalid or expired token.";
        exit;
    }
}

if(isset($_POST['submit'])){
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password == $confirm_password){
        $password = password_hash($password, PASSWORD_DEFAULT);

        $update = $conn->prepare("UPDATE users SET password=? WHERE email=?");
        $update->execute([$password, $email]);

        $delete = $conn->prepare("DELETE FROM password_reset WHERE email=?");
        $delete->execute([$email]);

        echo "Your password has been reset. You can now <a href='login.php'>login</a>.";
    } else {
        echo "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
include("header.php");
?>
<section class="form-container">
    <form action="" method="post" enctype="multipart/form-data">
        <h3>Reset Password</h3>
        <input type="password" name="password" placeholder="Enter your new password" required class="box">
        <input type="password" name="confirm_password" placeholder="Confirm your new password" required class="box">
        <input type="submit" name="submit" value="Reset Password" required class="btn">
    </form>
</section>
</body>
</html>
-------!