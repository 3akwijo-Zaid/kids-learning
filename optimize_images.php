<?php
require_once 'config/database.php';

// Function to optimize image
function optimizeImage($source, $destination, $quality = 80) {
    $info = getimagesize($source);
    
    if ($info === false) {
        return false;
    }
    
    $width = $info[0];
    $height = $info[1];
    $type = $info[2];
    
    // Create new image
    $new_image = imagecreatetruecolor($width, $height);
    
    // Load source image
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source_image = imagecreatefromjpeg($source);
            break;
        case IMAGETYPE_PNG:
            $source_image = imagecreatefrompng($source);
            // Preserve transparency
            imagealphablending($new_image, false);
            imagesavealpha($new_image, true);
            break;
        default:
            return false;
    }
    
    // Copy and resize
    imagecopyresampled($new_image, $source_image, 0, 0, 0, 0, $width, $height, $width, $height);
    
    // Save optimized image
    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($new_image, $destination, $quality);
            break;
        case IMAGETYPE_PNG:
            imagepng($new_image, $destination, 9);
            break;
    }
    
    // Free up memory
    imagedestroy($source_image);
    imagedestroy($new_image);
    
    return true;
}

// Process all images in uploads directory
function processDirectory($dir) {
    $files = scandir($dir);
    
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        
        $path = $dir . '/' . $file;
        
        if (is_dir($path)) {
            processDirectory($path);
        } else {
            $info = getimagesize($path);
            if ($info !== false) {
                echo "Optimizing: $path\n";
                optimizeImage($path, $path);
            }
        }
    }
}

// Start optimization
echo "Starting image optimization...\n";
processDirectory('uploads');
echo "Image optimization completed!\n";
?> 