<?php
session_start();

include '../include/functions.php';
include '../include/db.php';

if (!isset($_SESSION['db']) || empty($_SESSION['db'])) {
    die("Error: Database session not set or empty.");
}

$UserId = $_SESSION['user_id'];
$sql = "UPDATE notifications SET status = 'read' WHERE user_id = $UserId";
mysqli_query($conn, $sql);
?>
