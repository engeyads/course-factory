<?php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the avatar selection
    $selectedAvatar = isset($_POST['photo']) ? $_POST['photo'] : '';

    // Handle the file upload
    $uploadDirectory = 'assets/images/avatars/';
    $uploadedFile = basename($_FILES['file']['name']);
    $uploadedFilePath = $uploadDirectory . $uploadedFile;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFilePath)) {
        // Resize and convert the uploaded image to WebP
        $webpFilename = resizeAndConvertToWebP($uploadedFilePath, 128, $uploadDirectory);

        if ($webpFilename) {
            // Update the database with the selected avatar and generated WebP filename
            $id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; // Adjust if needed

            if ($id > 0) {
                $sql = "UPDATE users SET photo=? WHERE id=?";
                $stmt = mysqli_prepare($conn, $sql);

                if ($stmt) {
                    // Bind the parameters
                    mysqli_stmt_bind_param($stmt, "si", $webpFilename, $id);

                    // Execute the statement
                    $result = mysqli_stmt_execute($stmt);

                    if ($result) {
                        echo "User photo updated successfully";
                    } else {
                        echo "Error updating user photo: " . mysqli_error($conn);
                    }

                    // Close the statement
                    mysqli_stmt_close($stmt);
                } else {
                    echo "Error preparing SQL statement: " . mysqli_error($conn);
                }
            } else {
                echo "Invalid user ID";
            }

            // For demonstration purposes, let's just echo the selected avatar and generated WebP filename
            echo "Selected Avatar: $selectedAvatar<br>";
            echo "WebP Filename: $webpFilename<br>";
        } else {
            echo "Error resizing and converting the image.";
        }
    } else {
        echo "File upload failed.";
    }
}

// Function to resize and convert an image to WebP format using GD library
function resizeAndConvertToWebP($filePath, $newSize, $uploadDirectory) {
    // Generate a unique filename
    $uniqueFilename = uniqid('', true);

    list($width, $height, $type) = getimagesize($filePath);

    // Calculate the aspect ratio
    $ratio = $newSize / max($width, $height);

    // Calculate the new dimensions
    $newWidth = $width * $ratio;
    $newHeight = $height * $ratio;

    // Create a new image resource
    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    // Load the original image based on its type
    switch ($type) {
        case IMAGETYPE_JPEG:
            $originalImage = imagecreatefromjpeg($filePath);
            break;
        case IMAGETYPE_PNG:
            $originalImage = imagecreatefrompng($filePath);
            break;
        case IMAGETYPE_GIF:
            $originalImage = imagecreatefromgif($filePath);
            break;
        // Add more cases as needed for other image types
        default:
            return false; // Unsupported image type
    }

    // Resize the image
    imagecopyresampled($newImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // Generate the filename for the WebP image
    $webpFilename = $uniqueFilename . '.webp';

    // Convert the image to WebP format and save with the generated filename
    if (imagewebp($newImage, $uploadDirectory . $webpFilename)) {
        // Free up memory
        imagedestroy($newImage);
        imagedestroy($originalImage);

        // Remove the original uploaded image
        unlink($filePath);

        // Return the generated WebP filename
        return $webpFilename;
    } else {
        // Error converting to WebP
        imagedestroy($newImage);
        imagedestroy($originalImage);
        return false;
    }
}
?>
