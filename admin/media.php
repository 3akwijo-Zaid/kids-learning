<?php
session_start();
require_once '../config/database.php';

$message = '';
$alertClass = 'alert-info';

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $alertClass = $_SESSION['alert_class'];
    unset($_SESSION['message']);
    unset($_SESSION['alert_class']);
}

// Replace the existing checkExistingMediaType function at the top of the file
function checkExistingMediaType($conn, $element_id, $media_type) {
    // Check both the media_elements table and the elements table
    $stmt = $conn->prepare("
        SELECT 
            (SELECT COUNT(*) FROM media_elements WHERE element_id = ? AND media_type = ?) as media_count,
            CASE 
                WHEN ? = 'image' THEN image_path
                WHEN ? = 'audio' THEN audio_path
                WHEN ? = 'video' THEN video_path
            END as existing_path
        FROM elements 
        WHERE id = ?
    ");
    
    $stmt->bind_param("issssi", $element_id, $media_type, $media_type, $media_type, $media_type, $element_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return ($row['media_count'] > 0 || !empty($row['existing_path']));
}

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$conn = getDBConnection();

// Replace the existing file upload handling section with this updated version
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['media_file'])) {
    $element_id = $_POST['element_id'] ?? 0;
    $type = $_POST['type'] ?? '';
    $file = $_FILES['media_file'];
    
    if ($file['error'] === UPLOAD_ERR_OK && !empty($element_id) && !empty($type)) {
        // Check for existing media type
        if (checkExistingMediaType($conn, $element_id, $type)) {
            $message = "Error: This element already has a {$type} file associated with it. Please delete the existing one first.";
        } else {
            // Get element and category info
            $stmt = $conn->prepare("SELECT e.id, e.name, c.name as category_name 
                                FROM elements e 
                                JOIN categories c ON e.category_id = c.id 
                                WHERE e.id = ?");
            $stmt->bind_param("i", $element_id);
            $stmt->execute();
            $element = $stmt->get_result()->fetch_assoc();
            
            if ($element) {
                $upload_dir = "../uploads/{$element['category_name']}/";
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $filename = strtolower($element['name']) . '_' . time() . '_' . basename($file['name']);
                $target_path = $upload_dir . $filename;
                
                if (move_uploaded_file($file['tmp_name'], $target_path)) {
                    // Start transaction
                    $conn->begin_transaction();
                    try {
                        // Insert into media_elements table
                        $relative_path = "uploads/{$element['category_name']}/$filename";
                        $stmt = $conn->prepare("INSERT INTO media_elements (element_id, file_path, media_type) VALUES (?, ?, ?)");
                        $stmt->bind_param("iss", $element_id, $relative_path, $type);
                        
                        if (!$stmt->execute()) {
                            throw new Exception('Error recording media association.');
                        }

                        // Update element's media path
                        $field = $type . '_path';
                        $stmt = $conn->prepare("UPDATE elements SET $field = ? WHERE id = ?");
                        $stmt->bind_param("si", $relative_path, $element_id);
                        
                        if (!$stmt->execute()) {
                            throw new Exception('Error updating element media path.');
                        }

                        $conn->commit();
                        $message = 'File uploaded and associated successfully!';
                    } catch (Exception $e) {
                        $conn->rollback();
                        // Delete the uploaded file if database operations failed
                        if (file_exists($target_path)) {
                            unlink($target_path);
                        }
                        $message = $e->getMessage();
                    }
                } else {
                    $message = 'Error uploading file.';
                }
            } else {
                $message = 'Element not found.';
            }
        }
    } else {
        $message = 'Please select an element and specify media type.';
    }
}

// Update the media deletion handling section:

// Handle media deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $file_path = $_POST['file'] ?? '';
    if (!empty($file_path)) {
        $full_path = "../" . $file_path;
        
        // Start transaction
        $conn->begin_transaction();
        try {
            // Get the media details from database
            $stmt = $conn->prepare("SELECT element_id, media_type FROM media_elements WHERE file_path = ?");
            $stmt->bind_param("s", $file_path);
            $stmt->execute();
            $media_result = $stmt->get_result()->fetch_assoc();

            if ($media_result) {
                // Update the element to remove the media path
                $field = $media_result['media_type'] . '_path';
                $stmt = $conn->prepare("UPDATE elements SET $field = NULL WHERE id = ?");
                $stmt->bind_param("i", $media_result['element_id']);
                if (!$stmt->execute()) {
                    throw new Exception('Failed to update element');
                }

                // Delete from media_elements table
                $stmt = $conn->prepare("DELETE FROM media_elements WHERE file_path = ?");
                $stmt->bind_param("s", $file_path);
                if (!$stmt->execute()) {
                    throw new Exception('Failed to delete media record');
                }
            }

            // Delete the physical file
            if (file_exists($full_path) && !unlink($full_path)) {
                throw new Exception('Failed to delete file');
            }

            $conn->commit();
            $_SESSION['message'] = "File deleted successfully!";
            $_SESSION['alert_class'] = "alert-success";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['message'] = "Error: " . $e->getMessage();
            $_SESSION['alert_class'] = "alert-danger";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}

// Add this after your existing POST handlers
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $original_path = $_POST['original_path'] ?? '';
    $new_element_id = $_POST['new_element_id'] ?? '';
    
    if (!empty($original_path) && !empty($new_element_id)) {
        $conn->begin_transaction();
        try {
            // Get new element info
            $stmt = $conn->prepare("
                SELECT e.id, e.name, c.name as category_name 
                FROM elements e 
                JOIN categories c ON e.category_id = c.id 
                WHERE e.id = ?
            ");
            $stmt->bind_param("i", $new_element_id);
            $stmt->execute();
            $new_element = $stmt->get_result()->fetch_assoc();
            
            if ($new_element) {
                // Get current media info
                $stmt = $conn->prepare("SELECT media_type FROM media_elements WHERE file_path = ?");
                $stmt->bind_param("s", $original_path);
                $stmt->execute();
                $media_info = $stmt->get_result()->fetch_assoc();
                
                if ($media_info) {
                    $file_name = basename($original_path);
                    $new_path = "uploads/{$new_element['category_name']}/$file_name";
                    
                    // Move file if needed
                    if ($original_path !== $new_path) {
                        $old_full_path = "../" . $original_path;
                        $new_full_path = "../" . $new_path;
                        
                        if (!file_exists(dirname($new_full_path))) {
                            mkdir(dirname($new_full_path), 0777, true);
                        }
                        
                        if (!rename($old_full_path, $new_full_path)) {
                            throw new Exception('Failed to move file');
                        }
                    }
                    
                    // Update database records
                    $stmt = $conn->prepare("
                        UPDATE media_elements 
                        SET element_id = ?, file_path = ? 
                        WHERE file_path = ?
                    ");
                    $stmt->bind_param("iss", $new_element_id, $new_path, $original_path);
                    if (!$stmt->execute()) {
                        throw new Exception('Failed to update media record');
                    }
                    
                    // Update element path
                    $field = $media_info['media_type'] . '_path';
                    $stmt = $conn->prepare("UPDATE elements SET $field = ? WHERE id = ?");
                    $stmt->bind_param("si", $new_path, $new_element_id);
                    if (!$stmt->execute()) {
                        throw new Exception('Failed to update element record');
                    }
                    
                    $conn->commit();
                    $_SESSION['message'] = "Media updated successfully!";
                    $_SESSION['alert_class'] = "alert-success";
                } else {
                    throw new Exception('Media record not found');
                }
            } else {
                throw new Exception('Element not found');
            }
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['message'] = "Error: " . $e->getMessage();
            $_SESSION['alert_class'] = "alert-danger";
        }
        
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Modify the media files query section
// Replace the existing media files array population with this:

$media_files = [];
$query = "SELECT m.file_path, m.media_type, e.name as element_name, c.name as category_name
          FROM media_elements m
          JOIN elements e ON m.element_id = e.id
          JOIN categories c ON e.category_id = c.id
          ORDER BY c.name, e.name";

$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    $file_info = pathinfo($row['file_path']);
    $media_files[] = [
        'category' => $row['category_name'],
        'element' => $row['element_name'],
        'name' => $file_info['basename'],
        'path' => $row['file_path'],
        'type' => $file_info['extension']
    ];
}

// Get all categories
$categories = $conn->query("SELECT * FROM categories ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Library - Kids Learning Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Comic Sans MS', cursive, sans-serif;
        }
        .navbar {
            background: linear-gradient(45deg, #ff6b6b, #ff8e8e);
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: #ff6b6b;
            border-color: #ff6b6b;
        }
        .btn-primary:hover {
            background-color: #ff5252;
            border-color: #ff5252;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .media-preview {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }
        .alert {
            transition: opacity 0.5s ease-out;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">Kids Learning Hub</a>
            <div class="ms-auto">
                <a href="dashboard.php" class="btn btn-outline-light me-2">Dashboard</a>
                <a href="logout.php" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2>Media Library</h2>
            </div>
            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="bi bi-upload"></i> Upload Media
                </button>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert <?php echo $alertClass; ?>"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Preview</th>
                                <th>Category</th>
                                <th>Element</th>
                                <th>Filename</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($media_files as $file): ?>
                                <tr>
                                    <td>
                                        <?php if (in_array($file['type'], ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                            <img src="../<?php echo htmlspecialchars($file['path']); ?>" 
                                                 class="media-preview" alt="Preview">
                                        <?php elseif (in_array($file['type'], ['mp3', 'wav'])): ?>
                                            <i class="bi bi-music-note-beamed" style="font-size: 2rem;"></i>
                                        <?php elseif (in_array($file['type'], ['mp4', 'webm'])): ?>
                                            <i class="bi bi-camera-video" style="font-size: 2rem;"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo ucfirst(htmlspecialchars($file['category'])); ?></td>
                                    <td><?php echo htmlspecialchars($file['element']); ?></td>
                                    <td><?php echo htmlspecialchars($file['name']); ?></td>
                                    <td><?php echo strtoupper(htmlspecialchars($file['type'])); ?></td>
                                    <td>
                                        <a href="../<?php echo htmlspecialchars($file['path']); ?>" 
                                           class="btn btn-sm btn-success" target="_blank">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-warning edit-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal"
                                                data-file="<?php echo htmlspecialchars($file['path']); ?>"
                                                data-category="<?php echo htmlspecialchars($file['category']); ?>"
                                                data-element="<?php echo htmlspecialchars($file['element']); ?>">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <form method="POST" class="d-inline delete-form">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="file" value="<?php echo htmlspecialchars($file['path']); ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Media</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Select a category</option>
                                <?php while ($category = $categories->fetch_assoc()): ?>
                                    <option value="<?php echo $category['id']; ?>">
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="element_id" class="form-label">Element</label>
                            <select class="form-select" id="element_id" name="element_id" required>
                                <option value="">First select a category</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Media Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">Select a type</option>
                                <option value="image">Image</option>
                                <option value="audio">Audio</option>
                                <option value="video">Video</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="media_file" class="form-label">File</label>
                            <input type="file" class="form-control" id="media_file" name="media_file" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Media</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="original_path" id="edit_original_path">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_category_id" class="form-label">Category</label>
                            <select class="form-select" id="edit_category_id" name="new_category_id" required>
                                <?php 
                                $categories->data_seek(0);
                                while ($category = $categories->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo $category['id']; ?>">
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_element_id" class="form-label">Element</label>
                            <select class="form-select" id="edit_element_id" name="new_element_id" required>
                                <option value="">First select a category</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('category_id').addEventListener('change', function() {
        loadElements('category_id', 'element_id');
    });

    document.getElementById('edit_category_id').addEventListener('change', function() {
        loadElements('edit_category_id', 'edit_element_id');
    });

    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const filePath = this.dataset.file;
            document.getElementById('edit_original_path').value = filePath;
            
            // Set initial category and trigger element load
            const categoryId = this.closest('tr').querySelector('[data-category-id]').dataset.categoryId;
            const elementId = this.closest('tr').querySelector('[data-element-id]').dataset.elementId;
            
            const categorySelect = document.getElementById('edit_category_id');
            categorySelect.value = categoryId;
            loadElements('edit_category_id', 'edit_element_id', elementId);
        });
    });

    function loadElements(categorySelectId, elementSelectId, selectedElementId = null) {
        const categoryId = document.getElementById(categorySelectId).value;
        const elementSelect = document.getElementById(elementSelectId);
        
        elementSelect.innerHTML = '<option value="">Loading...</option>';
        
        if (categoryId) {
            fetch('get_elements.php?category_id=' + categoryId)
                .then(response => response.json())
                .then(elements => {
                    elementSelect.innerHTML = '<option value="">Select an element</option>';
                    elements.forEach(element => {
                        const option = document.createElement('option');
                        option.value = element.id;
                        option.textContent = element.name;
                        if (selectedElementId && element.id === selectedElementId) {
                            option.selected = true;
                        }
                        elementSelect.appendChild(option);
                    });
                });
        }
    }

    // Add delete button handler
    document.querySelectorAll('form[method="POST"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (this.querySelector('input[name="action"][value="delete"]')) {
                const button = this.querySelector('button[type="submit"]');
                button.disabled = true;
                button.innerHTML = '<i class="bi bi-hourglass-split"></i> Deleting...';
            }
        });
    });

    // Alert auto-hide functionality
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.querySelector('.alert');
        if (alert) {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 500);
            }, 3000); // Message will stay for 3 seconds
        }
    });
    </script>
</body>
</html>