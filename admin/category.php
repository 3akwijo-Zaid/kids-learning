<?php
session_start();
require_once '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$conn = getDBConnection();
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? 0;
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    
    if (empty($name)) {
        $error = 'Category name is required';
    } else {
        if ($action === 'add') {
            $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->bind_param("s", $name);
            if ($stmt->execute()) {
                $success = 'Category added successfully';
            } else {
                $error = 'Error adding category';
            }
        } elseif ($action === 'edit' && $id > 0) {
            $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
            $stmt->bind_param("si", $name, $id);
            if ($stmt->execute()) {
                $success = 'Category updated successfully';
            } else {
                $error = 'Error updating category';
            }
        }
    }
}

// Handle delete action
if ($action === 'delete' && $id > 0) {
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header('Location: index.php');
        exit();
    } else {
        $error = 'Error deleting category';
    }
}

// Get category data for edit
$category = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();
    
    if (!$category) {
        header('Location: index.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($action); ?> Category - Kids Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Kids Learning Admin</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0"><?php echo ucfirst($action); ?> Category</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="name" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?php echo htmlspecialchars($category['name'] ?? ''); ?>" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo $action === 'add' ? 'Add Category' : 'Update Category'; ?>
                                </button>
                                <a href="index.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 