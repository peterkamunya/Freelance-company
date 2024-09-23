<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = $_POST['message'];
    $user = 'Admin'; // Example: Set the admin username here

    try {
        $insert_stmt = $conn->prepare("INSERT INTO live_chat (user, message, timestamp) VALUES (?, ?, NOW())");
        $insert_stmt->execute([$user, $message]);

        // Redirect back to admin panel after sending message
        header("Location: adminpanel.php#chat");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die(); // Stop execution on error
    }
} else {
    // Redirect if accessed directly without POST data
    header("Location: adminpanel.php");
    exit();
}
?>
