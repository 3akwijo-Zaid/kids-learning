<?php
session_start();
require_once '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$conn = getDBConnection();

// Get categories
$categories = $conn->query("SELECT * FROM categories ORDER BY name");

// Get elements count per category
$elements_count = [];
$result = $conn->query("SELECT category_id, COUNT(*) as count FROM elements GROUP BY category_id");
while ($row = $result->fetch_assoc()) {
    $elements_count[$row['category_id']] = $row['count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Kids Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Kids Learning Admin</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col">
                <h2>Categories</h2>
                <a href="category.php?action=add" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Add Category
                </a>
            </div>
        </div>

        <div class="row">
            <?php while ($category = $categories->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h5>
                            <p class="card-text">
                                Elements: <?php echo $elements_count[$category['id']] ?? 0; ?>
                            </p>
                            <div class="btn-group">
                                <a href="category.php?action=edit&id=<?php echo $category['id']; ?>" 
                                   class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="category.php?action=delete&id=<?php echo $category['id']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to delete this category?');">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </div>
                            <a href="elements.php?category_id=<?php echo $category['id']; ?>" 
                               class="btn btn-info btn-sm mt-2">
                                <i class="bi bi-list"></i> Manage Elements
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 