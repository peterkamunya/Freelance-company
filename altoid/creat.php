
<?php 
include('config.php');

session_start();

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $name = filter_var($name,FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email,FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $password = filter_var($password,FILTER_SANITIZE_STRING);
    $cpassword = $_POST['cpassword'];
    $cpassword = filter_var($cpassword,FILTER_SANITIZE_STRING);

    $select = $conn->prepare("SELECT * FROM users WHERE email=?");
    $select->execute([$email]);

    if($select->rowCount()>0){
        $message[] ="User already exist";
    }else{
        if($password != $cpassword){
            $message[] ="Confirm password does not match";
        }else{
            $insert = $conn->prepare("INSERT INTO users(name,email,password) VALUES(?,?,?)");
            $insert->execute([$name,$email,$password]);

                    $message[] ="registered successfully";
                    header('location:login.php');
                }
            }
        }


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php  
   if(isset($message)){
    foreach($message as $message){
        echo'
        <div class="message">
            <span>'.$message.'</span>
        </div>
        ';
    }
   }

?>
<section class="form-container">
    <form action="" method="post">
        <h3>register now</h3>
        <input type="text" name="name" placeholder="enter your name" required class="box">
        <input type= "email" name= "email" placeholder="enter your email" required class="box">
        <input type="password" name="password" placeholder="enter your password" required class="box">
        <input type="password" name="cpassword" placeholder="confirm your password" required class="box">
        <input type="submit" name="submit" value="register now" required class="btn">
        <p>Already have an account? <a href="login.php">login now</a></p>
    </form>
</section>
</body>
</html>
