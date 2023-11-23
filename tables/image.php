<?php
function displayThumbnail($imagePath, $thumbnailWidth, $thumbnailHeight) {
    // Load the WebP image
    $image = imagecreatefromwebp($imagePath);
    if ($image === false) {
        echo 'Failed to load the WebP image.';
        return;
    }
    // Get the original image dimensions
    $originalWidth = imagesx($image);
    $originalHeight = imagesy($image);
    // Calculate the thumbnail dimensions with aspect ratio preserved
    $aspectRatio = $originalWidth / $originalHeight;
    if ($thumbnailWidth / $thumbnailHeight > $aspectRatio) {
        $thumbnailWidth = $thumbnailHeight * $aspectRatio;
    } else {
        $thumbnailHeight = $thumbnailWidth / $aspectRatio;
    }
    // Create a new thumbnail image
    $thumbnail = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);
    // Resize and crop the original image to fit the thumbnail dimensions
    imagecopyresampled(
        $thumbnail,
        $image,
        0,
        0,
        0,
        0,
        $thumbnailWidth,
        $thumbnailHeight,
        $originalWidth,
        $originalHeight
    );
    // Output the thumbnail to the browser
    header('Content-Type: image/webp');
    imagewebp($thumbnail);
    // Clean up memory
    imagedestroy($image);
    imagedestroy($thumbnail);
}
$imagePath = $_GET['img'];
$thumbnailWidth = 200;
$thumbnailHeight = 150;
displayThumbnail($imagePath, $thumbnailWidth, $thumbnailHeight);
?>