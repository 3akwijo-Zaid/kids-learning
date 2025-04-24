<?php
require_once 'config/database.php';

$conn = getDBConnection();

// Get all categories
$categories = $conn->query("SELECT * FROM categories ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kids Learning Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Comic Sans MS', cursive, sans-serif;
        }
        .category-card {
            transition: transform 0.3s;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .category-card:hover {
            transform: translateY(-10px);
        }
        .category-icon {
            font-size: 3rem;
            color: #ff6b6b;
        }
        .navbar {
            background: linear-gradient(45deg, #ff6b6b, #ff8e8e);
        }
        .btn-primary {
            background-color: #ff6b6b;
            border-color: #ff6b6b;
        }
        .btn-primary:hover {
            background-color: #ff5252;
            border-color: #ff5252;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-book"></i> Kids Learning Hub
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="admin/login.php">
                    <i class="bi bi-lock"></i> Admin Login
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="text-center mb-5">
            <h1 class="display-4">Welcome to Kids Learning Hub!</h1>
            <p class="lead">Let's learn something new today!</p>
        </div>

        <div class="row">
            <?php while ($category = $categories->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card category-card">
                        <div class="card-body text-center">
                            <div class="category-icon mb-3">
                                <?php
                                switch(strtolower($category['name'])) {
                                    case 'animals':
                                        echo '<i class="bi bi-emoji-smile"></i>';
                                        break;
                                    case 'alphabets':
                                        echo '<i class="bi bi-type"></i>';
                                        break;
                                    case 'transport':
                                        echo '<i class="bi bi-truck"></i>';
                                        break;
                                    case 'colors':
                                        echo '<i class="bi bi-palette"></i>';
                                        break;
                                    case 'shapes':
                                        echo '<i class="bi bi-square"></i>';
                                        break;
                                    case 'fruits':
                                        echo '<i class="bi bi-basket"></i>';
                                        break;
                                    default:
                                        echo '<i class="bi bi-book"></i>';
                                }
                                ?>
                            </div>
                            <h3 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h3>
                            <a href="category.php?id=<?php echo $category['id']; ?>" class="btn btn-primary">
                                Let's Learn!
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer class="bg-light mt-5 py-3">
        <div class="container text-center">
            <p class="mb-0">© 2025 Kids Learning Hub. All rights reserved</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 