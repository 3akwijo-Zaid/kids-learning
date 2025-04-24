<?php
require_once 'config/database.php';

// Function to generate a colored image
function generateImage($width, $height, $color, $text, $filename) {
    $image = imagecreatetruecolor($width, $height);
    
    // Allocate colors
    $bg = imagecolorallocate($image, $color[0], $color[1], $color[2]);
    $text_color = imagecolorallocate($image, 255, 255, 255);
    
    // Fill background
    imagefill($image, 0, 0, $bg);
    
    // Add text
    $font = 5; // Use built-in font
    $text_width = imagefontwidth($font) * strlen($text);
    $text_height = imagefontheight($font);
    
    $x = ($width - $text_width) / 2;
    $y = ($height - $text_height) / 2;
    
    imagestring($image, $font, $x, $y, $text, $text_color);
    
    // Save image
    imagejpeg($image, $filename, 90);
    imagedestroy($image);
}

// Colors for different categories
$colors = [
    'animals' => [139, 69, 19],    // Brown
    'alphabets' => [0, 128, 0],    // Green
    'transport' => [0, 0, 255],    // Blue
    'fruits' => [255, 0, 0],       // Red
    'colors' => [255, 255, 0],     // Yellow
    'shapes' => [128, 0, 128]      // Purple
];

// Create directories if they don't exist
foreach (array_keys($colors) as $category) {
    $dir = "uploads/$category";
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Generate images for each category
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT id, name, category_id FROM elements");
$stmt->execute();
$result = $stmt->get_result();

while ($element = $result->fetch_assoc()) {
    // Get category name
    $cat_stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
    $cat_stmt->bind_param("i", $element['category_id']);
    $cat_stmt->execute();
    $category = $cat_stmt->get_result()->fetch_assoc()['name'];
    
    $category = strtolower($category);
    $filename = "uploads/$category/" . strtolower($element['name']) . ".jpg";
    
    if (!file_exists($filename)) {
        echo "Generating image for: " . $element['name'] . "\n";
        generateImage(400, 300, $colors[$category], $element['name'], $filename);
    }
}

echo "Image generation completed!";
?> 