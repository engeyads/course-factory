<?php
session_start();

include '../include/functions.php';
include '../include/db.php';

if (!isset($_SESSION['db']) || empty($_SESSION['db'])) {
    die("Error: Database session not set or empty.");
}

// SQL query to create 'notifications' table if not exists
$sql = "CREATE TABLE IF NOT EXISTS notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    message VARCHAR(255),
    uid INT,
    status VARCHAR(10),
    notified BOOLEAN NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Execute the query
if (mysqli_query($conn, $sql)) {
    $notifications[] = array(
        'message' => "Table created or already exists successfully"
    );
} else {
    $notifications[] = array(
        'message' => "Error creating table: " . mysqli_error($conn)
    );
}

$UserId = $_SESSION['user_id'];
$where = "AND user_id = $UserId";

// Fetch notifications with a limit of 10
// $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

// Get the latest timestamp from the client-side
$latestTimestamp = isset($_GET['latest_timestamp']) ? $_GET['latest_timestamp'] : '';
// Update the query to fetch new notifications based on the latest timestamp
if (isset($_GET['latest_timestamp']) && $_GET['latest_timestamp'] != '') {
    // Fetch only new notifications
    $latestTimestamp = mysqli_real_escape_string($conn, $_GET['latest_timestamp']);
    $sql = "SELECT * FROM notifications WHERE 1 $where AND created_at > '$latestTimestamp' ORDER BY created_at DESC";
} else {
    // Fetch older notifications for pagination
    $offset = isset($_GET['offset']) ? mysqli_real_escape_string($conn, (int)$_GET['offset']) : 0;
    $sql = "SELECT * FROM notifications WHERE 1 $where ORDER BY created_at DESC LIMIT 10 OFFSET $offset";
}
$result = mysqli_query($conn, $sql);
$notifications = array();

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $unreadClass = ($row['status'] === 'unread') ? 'unread' : '';
        $imgqry = "SELECT `photo` FROM `users` WHERE `id`='".$row['uid']."'";
        $imgres = mysqli_query($conn, $imgqry);
        $img = mysqli_fetch_assoc($imgres);
        $img = $img['photo'];
        $notifications[] = array(
            'notification_id' => $row['notification_id'],
            'message' => $row['message'],
            'created_at' => $row['created_at'],
            'img' => $img,
            'unread' => $row['status'] === 'unread',
            'unreadClass' => $unreadClass,
            'notified' => $row['notified']
            // Add more fields as needed
        );
    }
}

$sql = "SELECT count(*) as cnt FROM notifications WHERE 1 $where AND status='unread'";
$res = mysqli_query($conn, $sql);

if ($res && mysqli_num_rows($res) > 0) {
    $ro = mysqli_fetch_assoc($res);
    $totalUnreadCount = $ro['cnt'];
} else {
    $totalUnreadCount = 0;
}



if (isset($isLocal)) {
    header('Content-Type: application/json');
    ob_clean();
}
echo json_encode(['notifications' => $notifications, 'totalUnreadCount' => $totalUnreadCount, 'latestTimestamp' => $latestTimestamp]);
if (isset($isLocal)) {
    ob_flush();
}
?>
