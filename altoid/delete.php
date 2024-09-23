<?php
include 'config.php';

if (isset($_GET['id'])) {
    $jobsId = $_GET['id'];

    // Prepare the delete statement
    $delete_jobs = $conn->prepare("DELETE FROM jobs WHERE id = :id");
    $delete_jobs->bindParam(':id', $jobsId, PDO::PARAM_INT);

    if ($delete_jobs->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
