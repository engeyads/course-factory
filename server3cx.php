<?php
// Define server IP and port
$serverIP = '51.68.206.183'; // Change this to your server's IP address
$serverPort = 3221;

// Create a TCP/IP socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

if ($socket === false) {
    echo "Failed to create socket: " . socket_strerror(socket_last_error()) . PHP_EOL;
    exit;
}

// Bind the socket to the specified address and port
if (!socket_bind($socket, $serverIP, $serverPort)) {
    echo "Failed to bind socket: " . socket_strerror(socket_last_error($socket)) . PHP_EOL;
    exit;
}

// Start listening for incoming connections
if (!socket_listen($socket)) {
    echo "Failed to listen on socket: " . socket_strerror(socket_last_error($socket)) . PHP_EOL;
    exit;
}

echo "Server listening on $serverIP:$serverPort..." . PHP_EOL;

while (true) {
    // Accept incoming connections
    $clientSocket = socket_accept($socket);

    if ($clientSocket === false) {
        echo "Failed to accept incoming connection: " . socket_strerror(socket_last_error($socket)) . PHP_EOL;
        continue;
    }

    // Read data from the client
    $data = socket_read($clientSocket, 1024);

    // Display the received CDR data
    echo "Received CDR data: " . $data . PHP_EOL;

    // Send a response back to the client if needed
    $response = "Received CDR data successfully";
    socket_write($clientSocket, $response, strlen($response));

    // Close the client socket
    socket_close($clientSocket);
}

// Close the main socket (this part will not be reached in the example)
socket_close($socket);
?>
