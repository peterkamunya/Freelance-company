<?php
include 'config.php';
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$selected_user_id = $_GET['user_id'] ?? null;

// Handle message submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $user_id = $_POST['user_id'];
    $message = trim($_POST['message']);

    if (!empty($message) && !empty($user_id)) {
        $stmt = $conn->prepare("INSERT INTO live_chat (user, message, user_type) VALUES (?, ?, 'admin')");
        $stmt->execute([$user_id, $message]);
        // Redirect to avoid resubmission on refresh
        header('Location: adminpanel.php?user_id=' . urlencode($user_id) . '#chat');
        exit();
    }
}

// Handle message reply from admin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply_message'])) {
    $message_id = $_POST['message_id'];
    $reply = $_POST['reply'];

    $reply_stmt = $conn->prepare("UPDATE live_chat SET reply = ? WHERE id = ?");
    $reply_stmt->execute([$reply, $message_id]);

    header("Location: adminpanel.php?user_id=" . urlencode($selected_user_id) . "#chat");
    exit();
}

// Handle job status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $job_id = $_POST['job_id'];
    $status = $_POST['status'];

    $update_status = $conn->prepare("UPDATE jobs SET status = ? WHERE id = ?");
    $update_status->execute([$status, $job_id]);

    header("Location: adminpanel.php#jobs");
    exit();
}

// Handle file upload for received files
if (isset($_POST['upload_received_file'])) {
    $job_id = $_POST['job_id'];
    $received_file = $_FILES['received_file']['name'];
    $target_dir = __DIR__ . "/uploaded_files/";
    $target_file = $target_dir . basename($received_file);

    // Check if directory exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Move uploaded file to the target directory
    if (move_uploaded_file($_FILES['received_file']['tmp_name'], $target_file)) {
        $update_file = $conn->prepare("UPDATE jobs SET received_file = ? WHERE id = ?");
        $update_file->execute([$received_file, $job_id]);
    } else {
        echo 'Failed to upload file.';
    }
}

// Ensure that messages are displayed for the selected user
if ($selected_user_id) {
    $select_messages = $conn->prepare("SELECT * FROM live_chat WHERE user = ? ORDER BY timestamp ASC");
    $select_messages->execute([$selected_user_id]);
} else {
    $select_messages = [];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
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
        .reply-form {
            display: none; /* Initially hide reply forms */
            margin-top: 10px;
        }
        .reply-form.active {
            display: block;
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
        h1, h2 {
            text-align: center;
        }
        h1 {
            color: blueviolet;
        }
        h2 {
            color: blueviolet;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Admin Panel</h1>
        <h2>StudentSuccessHub</h2>
        <ul class="nav nav-tabs" id="adminTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="users-tab" data-toggle="tab" href="#users" role="tab" aria-controls="users" aria-selected="true">Users page</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="jobs-tab" data-toggle="tab" href="#jobs" role="tab" aria-controls="jobs" aria-selected="false">Job page</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="chat-tab" data-toggle="tab" href="#chat" role="tab" aria-controls="chat" aria-selected="false">Live Chat</a>
            </li>
        </ul>

        <div class="tab-content" id="adminTabsContent">
            <div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">
                <h2 class="my-4">Manage Users Information</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Password</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <?php 
                        $select_users = $conn->prepare("SELECT * FROM users");
                        $select_users->execute();
                        if ($select_users->rowCount() > 0) {
                            while ($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($fetch_users['id']); ?></td>
                            <td><?= htmlspecialchars($fetch_users['name']); ?></td>
                            <td><?= htmlspecialchars($fetch_users['email']); ?></td>
                            <td><?= htmlspecialchars($fetch_users['password']); ?></td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo '<p class="empty">No users yet</p>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="jobs" role="tabpanel" aria-labelledby="jobs-tab">
                <h2 class="my-4">Manage Jobs</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Uploaded File</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="jobsTableBody">
                        <?php 
                        $select_jobs = $conn->prepare("SELECT * FROM jobs");
                        $select_jobs->execute();
                        if ($select_jobs->rowCount() > 0) {
                            while ($fetch_jobs = $select_jobs->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($fetch_jobs['id']); ?></td>
                            <td><?= htmlspecialchars($fetch_jobs['title']); ?></td>
                            <td><?= htmlspecialchars($fetch_jobs['description']); ?></td>
                            <td>
                                <?php
                                $file = htmlspecialchars($fetch_jobs['image']);
                                $fileType = htmlspecialchars($fetch_jobs['file_type']);
                                
                                if ($file) {
                                    if (strpos($fileType, 'image') !== false) {
                                        echo '<img src="uploaded_img/' . $file . '" alt="" width="50px">';
                                    } elseif (strpos($fileType, 'pdf') !== false) {
                                        echo '<a href="uploaded_img/' . $file . '" target="_blank">Download PDF</a>';
                                    } elseif (strpos($fileType, 'doc') !== false || strpos($fileType, 'docx') !== false) {
                                        echo '<a href="uploaded_img/' . $file . '" target="_blank">Download DOC</a>';
                                    } else {
                                        echo '<a href="uploaded_img/' . $file . '" target="_blank">Download File</a>';
                                    }
                                } else {
                                    echo 'No file';
                                }
                                ?>
                            </td>
                            <td>
                                <form action="adminpanel.php" method="post">
                                    <input type="hidden" name="job_id" value="<?= htmlspecialchars($fetch_jobs['id']); ?>">
                                    <select name="status" class="box">
                                        <option value="pending" <?= $fetch_jobs['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="completed" <?= $fetch_jobs['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                    </select>
                                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                                </form>
                            </td>
                            <td>
                                <form action="adminpanel.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="job_id" value="<?= htmlspecialchars($fetch_jobs['id']); ?>">
                                    <input type="file" name="received_file" class="form-control">
                                    <button type="submit" name="upload_received_file" class="btn btn-secondary mt-2">Upload Received File</button>
                                </form>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo '<p class="empty">No jobs yet</p>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="chat" role="tabpanel" aria-labelledby="chat-tab">
                <h2 class="my-4">Live Chat</h2>
                <form action="adminpanel.php" method="post">
                    <div class="form-group">
                        <label for="user_id">Select User:</label>
                        <select name="user_id" id="user_id" class="form-control">
                            <?php
                            $select_users = $conn->prepare("SELECT * FROM users");
                            $select_users->execute();
                            while ($fetch_user = $select_users->fetch(PDO::FETCH_ASSOC)) {
                                echo '<option value="' . htmlspecialchars($fetch_user['id']) . '"' . ($selected_user_id == $fetch_user['id'] ? ' selected' : '') . '>' . htmlspecialchars($fetch_user['name']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea name="message" id="message" class="form-control"></textarea>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Send Message</button>
                </form>

                <div class="messages" id="chatMessages">
                    <?php
                    if ($selected_user_id) {
                        while ($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)) {
                            $message_id = htmlspecialchars($fetch_messages['id']);
                            $message = htmlspecialchars($fetch_messages['message']);
                            $user = htmlspecialchars($fetch_messages['user']);
                            $user_type = htmlspecialchars($fetch_messages['user_type']);
                            $reply = htmlspecialchars($fetch_messages['reply']);
                            $timestamp = htmlspecialchars($fetch_messages['timestamp']);

                            echo '<div class="message">';
                            echo '<strong>' . ($user_type === 'admin' ? 'Admin' : 'User ' . $user) . ':</strong> ';
                            echo $message . ' <small>(' . $timestamp . ')</small>';

                            if ($reply) {
                                echo '<br><strong>Reply:</strong> ' . $reply;
                            }

                            if ($user_type !== 'admin') {
                                echo '<button class="btn btn-secondary btn-sm reply-button" data-message-id="' . $message_id . '">Reply</button>';
                                echo '<form class="reply-form" data-message-id="' . $message_id . '" method="post">';
                                echo '<input type="hidden" name="message_id" value="' . $message_id . '">';
                                echo '<textarea name="reply" class="form-control" placeholder="Type your reply here"></textarea>';
                                echo '<button type="submit" name="reply_message" class="btn btn-primary btn-sm mt-2">Send Reply</button>';
                                echo '</form>';
                            }

                            echo '</div>';
                        }
                    } else {
                        echo '<p>Select a user to view messages.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        // Toggle reply form visibility
        document.querySelectorAll('.reply-button').forEach(button => {
            button.addEventListener('click', function() {
                const messageId = this.getAttribute('data-message-id');
                document.querySelector(`.reply-form[data-message-id="${messageId}"]`).classList.toggle('active');
            });
        });
    </script>
</body>
</html>
