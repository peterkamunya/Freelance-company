<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $email = $_POST['email'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID

    // Handle file upload
    $image = null;
    $file_type = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileType = $_FILES['image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $allowedExts = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx');
        
        if (in_array($fileExtension, $allowedExts)) {
            $uploadFileDir = './uploaded_img/';
            $dest_path = $uploadFileDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $image = $fileName;
                $file_type = $fileType; // Store file type
            }
        }
    }

    // Insert job with user_id and file_type
    $stmt = $conn->prepare("INSERT INTO jobs (title, email, description, image, status, user_id, file_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $email, $description, $image, $status, $user_id, $file_type]);

    header('Location: clientdashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Job</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include("header.php"); ?>
    <section class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Submit New Job</h3>
            <input type="text" name="title" placeholder="Job Title" required class="box">
            <input type="email" name="email" placeholder="Your Email" required class="box">
            <textarea name="description" placeholder="Job Description" required class="box"></textarea>
            <input type="file" name="image" class="box">
            <select name="status" required class="box">
                <option value="Pending">not yet</option>
                <option value="Completed">Confirmed</option>
            </select>
            <input type="submit" name="submit" value="Submit Job" class="btn">
        </form>
    </section>
    <div class="other">
        <a href="livechat.php" class="option-btn">Chat with admin</a>
        <a href="clientdashboard.php" class="option-btn">View Client Dashboard</a>
        <a href="survey.php" class="option-btn">Survey Form</a>
    </div>
</body>
</html>
