<?php




// Check if the image name is provided
if (isset($_GET['image'])) {
    $imageToDelete = $_GET['image'];
    $direc = '/'.$_GET['remoteDirectory'].'/'.str_replace("-", "/", $_GET['dir'])."/";
    $imagePath =  $direc . $imageToDelete;
    // Connect to FTP server

    $ftpConnection = connectToFtp($ftpServer, $ftpUsername, $ftpPassword);
    if ($ftpConnection) {
        $remoteFilePath = $direc . $imageToDelete;
        if (ftp_delete($ftpConnection, $remoteFilePath)) {
            $response['success'] = true;
            $response['message'] = 'File deleted successfully.';
            $response['image'] = $imageToDelete;

            delete_image($_GET['c'],$_GET['t'],$imageToDelete, $conn2);
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to delete the file from the remote server.';
        }

        // Delete the image from the FTP server
        //$deleteResult = deleteImage($ftpConnection, $imagePath);
        // Disconnect from FTP server
        disconnectFromFtp($ftpConnection);
    }

} else {
    // Return error response if the image name is not provided
    $response['success'] = false;
    $response['message'] = 'Image name not provided!';
}

$response = json_encode($response);

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

?>