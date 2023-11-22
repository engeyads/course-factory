<?php

$ftp_server = 'blackbird-training.co.uk';
$ftp_user = 'blackbirdukftp1@blackbird-training.co.uk';
$ftp_pass = 'X6Ls8C#Zc8o!iCTO';

// Connect
$connection = ftp_connect($ftp_server);

if (!$connection) {
    die("Could not connect to $ftp_server");
}
echo "Successfully connected to $ftp_server\n";

// Login
if (@ftp_login($connection, $ftp_user, $ftp_pass)) {
    echo "Logged in as $ftp_user@$ftp_server\n";
} else {
    die("Couldn't log in as $ftp_user\n");
}

// Switch to passive mode (often necessary with modern FTP servers)
ftp_pasv($connection, true);

// Upload a file
$local_file = 'file.txt';  // Replace with your local file path
$remote_file = 'remote_file.txt';  // Name as it should appear on the server

if (ftp_put($connection, $remote_file, $local_file, FTP_BINARY)) {
    echo "Successfully uploaded $local_file\n";
} else {
    echo "Error uploading $local_file\n";
}

// List the contents of the current directory
$directory_contents = ftp_nlist($connection, ".");

if ($directory_contents === false) {
    echo "Error fetching directory contents.\n";
} else {
    echo "Directory contents:\n";
    foreach ($directory_contents as $file) {
        echo $file . "\n";
    }
}

// Close the connection
ftp_close($connection);
echo "Connection closed.\n";
?>
