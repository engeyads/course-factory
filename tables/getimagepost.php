<?php
try {
// Connect to FTP server
// Connect
$connection = connectToFtp($ftpServer, $ftpUsername, $ftpPassword);
// $ftpConnection = connectToFtp($ftpServer, $ftpUsername, $ftpPassword);
//print_r($ftpConnection) ;
// Get the list of images in the upload folder
$images = getImagesInFolderpst($ftpServer,$connection,$_POST['t'],$_POST['url'],$conn2);

// output current directory name (/php)
echo ftp_pwd($connection);


// Generate HTML markup for displaying the images
$imageMarkup = '';
if (empty($images)) {
    $imageMarkup = 'No images found.';
}

// Disconnect from FTP server
disconnectFromFtp($connection);

// Prepare the JSON response
$response = json_encode($images);

// Clear any previously generated output
ob_clean();

// Set the appropriate headers
header('Content-Type: application/json');
header('Content-Length: ' . strlen($response));

// Send the JSON response
echo $response;
// Flush the output buffer
ob_flush();
exit; // Stop further execution
} catch (Exception $e) {
    // Handle exceptions here, e.g., log the error or provide a user-friendly error message.
    echo "An error occurred: " . $e->getMessage();
}
?>