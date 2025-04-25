<?php
// Update the updateMediaStatus function to match the table structure
function updateMediaStatus($conn, $mediaId, $status) {
    $validStatuses = ['active', 'deleted', 'permanently_deleted'];
    if (!in_array($status, $validStatuses)) {
        return false;
    }
    
    $stmt = $conn->prepare("UPDATE media_elements SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $mediaId);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

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
$missingFiles = [];
$deletedFiles = [];

// Fetch media files from database with correct path handling
$mediaFiles = [];
$result = $conn->query("SELECT me.id, me.file_path, me.status, me.media_type, 
                              c.name as category, e.name as element_name 
                       FROM media_elements me
                       JOIN elements e ON me.element_id = e.id 
                       JOIN categories c ON e.category_id = c.id
                       WHERE me.status != 'permanently_deleted'");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mediaFiles[] = $row;
    }
}

// Check physical files against database records
foreach ($mediaFiles as $dbFile) {
    // Construct the full file path using category and original filename
    $filePath = $mediaDir . '/' . $dbFile['category'] . '/' . basename($dbFile['file_path']);
    
    if (!file_exists($filePath)) {
        // File is missing from filesystem
        if ($dbFile['status'] !== 'deleted') {
            $deletedFiles[] = [
                'id' => $dbFile['id'],
                'path' => $dbFile['category'] . '/' . basename($dbFile['file_path']),
                'type' => $dbFile['media_type']
            ];
            updateMediaStatus($conn, $dbFile['id'], 'deleted');
        }
    } else {
        // File exists in filesystem
        if ($dbFile['status'] === 'deleted') {
            // Reactivate if previously marked as deleted
            updateMediaStatus($conn, $dbFile['id'], 'active');
            echo "Reactivated: " . $dbFile['category'] . '/' . basename($dbFile['file_path']) . "\n";
        }
    }
}

// Handle deleted files reporting
if (!empty($deletedFiles)) {
    error_log("Files deleted from uploads folder: " . implode(", ", array_column($deletedFiles, 'path')));
    echo "Warning: The following files were deleted from uploads folder:\n";
    foreach ($deletedFiles as $file) {
        echo "- {$file['path']} (Type: {$file['type']})\n";
    }
}

foreach ($categories as $category) {
    $categoryPath = "$mediaDir/$category";
    if (is_dir($categoryPath)) {
        $files = scandir($categoryPath);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $filePath = "$categoryPath/$file";
                if (file_exists($filePath)) {
                    $media["$category/$file"] = $filePath;
                } else {
                    $missingFiles[] = "$category/$file";
                }
            }
        }
    }
}

// Log missing files
if (!empty($missingFiles)) {
    error_log("Missing files detected in uploads folder: " . implode(", ", $missingFiles));
    echo "Warning: Some files are missing from the uploads folder.\n";
}

$conn->close();

// Download media files with improved error handling
foreach ($media as $file => $path) {
    $target = "uploads/$file";
    $targetDir = dirname($target);
    
    // Ensure target directory exists
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    try {
        if (!file_exists($target)) {
            $content = @file_get_contents($path);
            if ($content !== false) {
                if (file_put_contents($target, $content)) {
                    echo "Downloaded: $file\n";
                } else {
                    throw new Exception("Failed to write file");
                }
            } else {
                throw new Exception("Failed to read file");
            }
        }
    } catch (Exception $e) {
        error_log("Error processing file $file: " . $e->getMessage());
        echo "Failed to process: $file - " . $e->getMessage() . "\n";
    }
}

echo "Setup completed successfully!";
?>