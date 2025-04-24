<?php
// Fetch categories from the database to create necessary directories
$directories = ['uploads'];
$result = $conn->query("SELECT name FROM categories");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $directories[] = 'uploads/' . $row['name'];
    }
}

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
}
// Media file paths for local storage
// Database connection
$conn = new mysqli('localhost', 'root', '', 'kids_learning');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories from the database
$categories = [];
$result = $conn->query("SELECT name FROM categories");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['name'];
    }
}

$mediaDir = __DIR__ . '/uploads';
$media = [];
foreach ($categories as $category) {
    $categoryPath = "$mediaDir/$category";
    if (is_dir($categoryPath)) {
        $files = scandir($categoryPath);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $media["$category/$file"] = "$categoryPath/$file";
            }
        }
    }
}

$conn->close();

// Download media files
foreach ($media as $file => $url) {
    $target = "uploads/$file";
    if (!file_exists($target)) {
        $content = file_get_contents($url);
        if ($content !== false) {
            file_put_contents($target, $content);
            echo "Downloaded: $file\n";
        } else {
            echo "Failed to download: $file\n";
        }
    }
}

echo "Setup completed successfully!";
?> 