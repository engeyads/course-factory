<?php
session_start();

include '../include/db.php';

if (!isset($_SESSION['db']) || empty($_SESSION['db'])) {
    die("Error: Database session not set or empty.");
}

// Assuming you have already connected to your database

// Check if the notification_id is provided
if (isset($_POST['notification_id'])) {
    $notificationId = $_POST['notification_id'];

    // Update the notification status to 'read' for the specific notification_id
    $updateSql = "UPDATE notifications SET notified = '1' WHERE notification_id = $notificationId";
    $result = mysqli_query($conn, $updateSql);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Notification marked as notified successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error marking notification as notified']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Notification ID not provided']);
}
?>
