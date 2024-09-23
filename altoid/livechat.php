<?php
include 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = isset($_SESSION['user']) ? $_SESSION['user'] : 'Unknown'; // Default to 'Unknown' if session variable is not set

// Handle message submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $message = trim($_POST['message']);

    if (!empty($message)) {
        try {
            $stmt = $conn->prepare("INSERT INTO live_chat (user, message, user_type) VALUES (?, ?, 'user')");
            $stmt->execute([$user_id, $message]);
            // Redirect to avoid resubmission on refresh
            header('Location: chat.php');
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Message cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .messages {
            height: 300px;
            overflow-y: scroll;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            background: #f9f9f9;
        }
        .message {
            margin-bottom: 10px;
            padding: 10px;
            background: #e1e1e1;
            border-radius: 5px;
            position: relative;
        }
        .message.admin {
            background: #d1e7dd;
        }
        body {
            background-image: url('image/Black Illustrative Education Logo.png');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .container {
            border: 2px solid black;
            background-color: white;
            height: 50%;
            font-size: small;
        }
        a {
            color: blueviolet;
            background: white;
        }
        a:hover {
            color: blue;
            background-color: palevioletred;
        }
        h1 {
            color: blueviolet;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Live Chat</h1>

        <form action="chat.php" method="post">
            <div class="form-group">
                <textarea name="message" id="message" class="form-control" placeholder="Type your message here..."></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Send Message</button>
        </form>

        <div class="messages" id="chatMessages">
            <?php
            try {
                // Fetch messages
                $select_messages = $conn->prepare("SELECT * FROM live_chat WHERE user = ? OR user_type = 'admin' ORDER BY timestamp ASC");
                $select_messages->execute([$user_id]);

                if ($select_messages->rowCount() > 0) {
                    while ($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)) {
                        // Handle undefined array keys
                        $message = isset($fetch_messages['message']) ? htmlspecialchars($fetch_messages['message']) : 'No message content';
                        $user_type = isset($fetch_messages['user_type']) ? htmlspecialchars($fetch_messages['user_type']) : 'unknown';
                        $timestamp = isset($fetch_messages['timestamp']) ? htmlspecialchars($fetch_messages['timestamp']) : 'unknown time';

                        echo '<div class="message ' . ($user_type === 'admin' ? 'admin' : '') . '">';
                        echo '<strong>' . ($user_type === 'admin' ? 'Admin' : 'You') . ':</strong> ';
                        echo $message . ' <small>(' . $timestamp . ')</small>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No messages yet.</p>';
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>
        </div>
        <div class="other">
    <a href="submit.php" class="option-btn">Back</a>
    <a href="login.php" class="option-btn">LogOut</a>
 
    </div>

    </div>
    <!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/66a18e5fbecc2fed692a999b/1i3nbo20o';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
    
</body>
</html>
