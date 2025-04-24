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
                $name = $_POST['name'] ?? '';
                if (!empty($name)) {
                    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
                    $stmt->bind_param("s", $name);
                    if ($stmt->execute()) {
                        $message = 'Category added successfully!';
                    } else {
                        $message = 'Error adding category.';
                    }
                }
                break;
            case 'edit':
                $id = $_POST['id'] ?? 0;
                $name = $_POST['name'] ?? '';
                if (!empty($name) && $id > 0) {
                    $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
                    $stmt->bind_param("si", $name, $id);
                    if ($stmt->execute()) {
                        $message = 'Category updated successfully!';
                    } else {
                        $message = 'Error updating category.';
                    }
                }
                break;
            case 'delete':
                $id = $_POST['id'] ?? 0;
                if ($id > 0) {
                    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    if ($stmt->execute()) {
                        $message = 'Category deleted successfully!';
                    } else {
                        $message = 'Error deleting category.';
                    }
                }
                break;
        }
    }
}

// Get all categories
$categories = $conn->query("SELECT * FROM categories ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - Kids Learning Hub</title>
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
                <h2>Manage Categories</h2>
            </div>
            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="bi bi-plus-circle"></i> Add New Category
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
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($category = $categories->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($category['id']); ?></td>
                                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editCategoryModal"
                                                data-id="<?php echo $category['id']; ?>"
                                                data-name="<?php echo htmlspecialchars($category['name']); ?>">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
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

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle edit modal data
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editCategoryModal');
            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_name').value = name;
            });
        });
    </script>
</body>
</html> 