<?php

global $ftpServer;
global $ftpUsername;
global $ftpPassword;

$direc = '/'.$_GET['remoteDirectory'].'/'.str_replace("-", "/", $_GET['dir'])."/";

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];

        // Check for errors
        if ($file['error'] === UPLOAD_ERR_OK) {
            $tempFilePath = $file['tmp_name'];

            // Generate a unique filename
            $timestamp = time();
            $filename = $timestamp . '_' . $file['name'];

            // Connect to FTP server
            $ftpConnection = connectToFtp($ftpServer, $ftpUsername, $ftpPassword);

            if ($ftpConnection) {
                
                    // Set passive mode
                    ftp_pasv($ftpConnection, true);

                    // Upload the file to remote directory
                    $destinationPath = $direc . $filename;
                    if (ftp_put($ftpConnection, $destinationPath, $tempFilePath, FTP_BINARY)) {
                        $response['success'] = true;
                        $response['message'] = 'Upload successful.';
                        $response['image'] = $filename;
                        $response['imageUrl'] = 'https://' . $ftpServer.'/' . $destinationPath;
                    } else {
                        $response['success'] = false;
                        $response['message'] = 'Failed to upload the file to the remote server.';
                    }
                

                // Disconnect from FTP server
                disconnectFromFtp($ftpConnection);
            } else {
                $response['success'] = false;
                $response['message'] = 'Failed to connect to the FTP server.';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Error occurred during file upload.';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'No file uploaded.';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method.';
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


