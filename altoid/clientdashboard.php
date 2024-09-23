<?php
include 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle job deletion
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // Prepare the delete statement
    $delete_jobs = $conn->prepare("DELETE FROM jobs WHERE id = ? AND user_id = ?");
    $delete_jobs->execute([$delete_id, $user_id]);
}

// Fetch jobs for the logged-in user
$query = $conn->prepare('SELECT * FROM jobs WHERE user_id = ?');
$query->execute([$user_id]);
$jobs = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn-download {
            display: inline-block;
            padding: 10px 20px;
            background-color: blueviolet;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-download:hover {
            background-color: palevioletred;
        }
        .container {
            background: whitesmoke;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        th {
            color: white;
            background-color: blueviolet;
        }
        td {
            background-color: whitesmoke;
            color: black;
            font-size: small;
        }
    </style>
</head>
<body>
    <?php include("header.php"); ?>
    <div class="container">
        <header>
            <h1>Your Dashboard</h1>
            <h2>StudentSuccessHub</h2>
        </header>
        
        <main>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Email</th>
                        <th>Description</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Action</th>
                        <th>Received File</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($job['id']); ?></td>
                            <td><?php echo htmlspecialchars($job['title']); ?></td>
                            <td><?php echo htmlspecialchars($job['email']); ?></td>
                            <td><?php echo htmlspecialchars($job['description']); ?></td>
                            <td>
                                <?php if (!empty($job['image'])): ?>
                                    <a class="btn-download" href="uploaded_img/<?php echo htmlspecialchars($job['image']); ?>" download>Download</a>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($job['status']); ?></td>
                            <td>
                                <a href="clientdashboard.php?delete=<?= htmlspecialchars($job['id']); ?>" onclick="return confirm('Are you sure you want to delete?')" class="delete-btn">Delete</a>
                            </td>
                            <td>
                                <?php if (!empty($job['received_file'])): ?>
                                    <?php
                                    $received_file = htmlspecialchars($job['received_file']);
                                    $fileType = pathinfo($received_file, PATHINFO_EXTENSION);
                                    if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                                        echo '<img src="uploaded_files/' . $received_file . '" alt="" width="50px">';
                                    } elseif ($fileType === 'pdf') {
                                        echo '<a class="btn-download" href="uploaded_files/' . $received_file . '" target="_blank">Download PDF</a>';
                                    } elseif (in_array($fileType, ['doc', 'docx'])) {
                                        echo '<a class="btn-download" href="uploaded_files/' . $received_file . '" target="_blank">Download DOC</a>';
                                    } else {
                                        echo '<a class="btn-download" href="uploaded_files/' . $received_file . '" target="_blank">Download File</a>';
                                    }
                                    ?>
                                <?php else: ?>
                                    No received file
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
    <div class="other">
        <a href="submit.php" class="option-btn">Submit New Job</a>
        <a href="submit.php" class="option-btn">Back</a>
        <a href="login.php" class="option-btn">Logout</a>
    </div>
</body>
</html>
