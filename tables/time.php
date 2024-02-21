<?php
// Get the server time
date_default_timezone_set('Europe/Istanbul');

$serverTime = time(); // You might want to use a more sophisticated method, e.g., from a database or an NTP server.

// Return the server time as JSON
echo json_encode(['serverTime' => date('Y-m-d H:i:s', $serverTime)]);
?>
