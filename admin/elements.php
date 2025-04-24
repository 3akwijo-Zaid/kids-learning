<?php
session_start();
require_once '../config/database.php';

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$conn = getDBConnection();
$message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $category_id = $_POST['category_id'] ?? 0;
                $name = $_POST['name'] ?? '';
                $description = $_POST['description'] ?? '';
                
                if (!empty($name) && $category_id > 0) {
                    $stmt = $conn->prepare("INSERT INTO elements (category_id, name, description) VALUES (?, ?, ?)");
                    $stmt->bind_param("iss", $category_id, $name, $description);
                    if ($stmt->execute()) {
                        $message = 'Element added successfully!';
                    } else {
                        $message = 'Error adding element.';
                    }
                }
                break;
            case 'edit':
                $id = $_POST['id'] ?? 0;
                $category_id = $_POST['category_id'] ?? 0;
                $name = $_POST['name'] ?? '';
                $description = $_POST['description'] ?? '';
                
                if (!empty($name) && $id > 0 && $category_id > 0) {
                    $stmt = $conn->prepare("UPDATE elements SET category_id = ?, name = ?, description = ? WHERE id = ?");
                    $stmt->bind_param("issi", $category_id, $name, $description, $id);
                    if ($stmt->execute()) {
                        $message = 'Element updated successfully!';
                    } else {
                        $message = 'Error updating element.';
                    }
                }
                break;
            case 'delete':
                $id = $_POST['id'] ?? 0;
                if ($id > 0) {
                    $stmt = $conn->prepare("DELETE FROM elements WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    if ($stmt->execute()) {
                        $message = 'Element deleted successfully!';
                    } else {
                        $message = 'Error deleting element.';
                    }
                }
                break;
        }
    }
}

// Get all categories for dropdown
$categories = $conn->query("SELECT * FROM categories ORDER BY name");

// Get all elements with category names
$elements = $conn->query("
    SELECT e.*, c.name as category_name 
    FROM elements e 
    JOIN categories c ON e.category_id = c.id 
    ORDER BY c.name, e.name
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Elements - Kids Learning Hub</title>
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
                <h2>Manage Learning Elements</h2>
            </div>
            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addElementModal">
                    <i class="bi bi-plus-circle"></i> Add New Element
                </button>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Category</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($element = $elements->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($element['id']); ?></td>
                                    <td><?php echo htmlspecialchars($element['category_name']); ?></td>
                                    <td><?php echo htmlspecialchars($element['name']); ?></td>
                                    <td><?php echo htmlspecialchars($element['description']); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editElementModal"
                                                data-id="<?php echo $element['id']; ?>"
                                                data-category-id="<?php echo $element['category_id']; ?>"
                                                data-name="<?php echo htmlspecialchars($element['name']); ?>"
                                                data-description="<?php echo htmlspecialchars($element['description']); ?>">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this element?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $element['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Element Modal -->
    <div class="modal fade" id="addElementModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Element</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
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
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Element</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Element Modal -->
    <div class="modal fade" id="editElementModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Element</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label for="edit_category_id" class="form-label">Category</label>
                            <select class="form-select" id="edit_category_id" name="category_id" required>
                                <option value="">Select a category</option>
                                <?php 
                                $categories->data_seek(0); // Reset the categories result pointer
                                while ($category = $categories->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo $category['id']; ?>">
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Element</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle edit modal data
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editElementModal');
            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const categoryId = button.getAttribute('data-category-id');
                const name = button.getAttribute('data-name');
                const description = button.getAttribute('data-description');
                
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_category_id').value = categoryId;
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_description').value = description;
            });
        });
    </script>
</body>
</html> 