<?php
require_once 'config/database.php';

$conn = getDBConnection();
$category_id = $_GET['id'] ?? 0;

// Get category details
$stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();

if (!$category) {
    header('Location: index.php');
    exit();
}

// Get elements for this category
$stmt = $conn->prepare("SELECT * FROM elements WHERE category_id = ? ORDER BY name");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$elements = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category['name']); ?> - Kids Learning Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Comic Sans MS', cursive, sans-serif;
        }
        .element-card {
            transition: transform 0.3s;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            background-color: white;
            height: 100%;
            display: flex;
            flex-direction: column;
            min-height: 400px;
        }
        .element-card:hover {
            transform: translateY(-5px);
        }
        .element-card .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
        }
        .element-card .card-text {
            flex-grow: 1;
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
        .audio-player, .video-player {
            width: 100%;
            margin-top: 10px;
        }
        .element-image {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        .alphabet-icon {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            font-size: 8rem;
            color: #ff6b6b;
        }
        .card-title {
            font-size: 2rem;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left"></i> Back to Home
            </a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="text-center mb-5">
            <h1 class="display-4"><?php echo htmlspecialchars($category['name']); ?></h1>
            <p class="lead">Let's learn about <?php echo strtolower(htmlspecialchars($category['name'])); ?>!</p>
        </div>

        <div class="row">
            <?php while ($element = $elements->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card element-card">
                        <?php if (!empty($element['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($element['image_path']); ?>" 
                                 class="card-img-top element-image" 
                                 alt="<?php echo htmlspecialchars($element['name']); ?>">
                        <?php else: ?>
                            <div class="alphabet-icon">
                                <?php echo htmlspecialchars($element['name']); ?>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h3 class="card-title"><?php echo htmlspecialchars($element['name']); ?></h3>
                            <p class="card-text"><?php echo htmlspecialchars($element['description']); ?></p>
                            
                            <?php if (!empty($element['audio_path'])): ?>
                                <div class="audio-player">
                                    <audio controls>
                                        <source src="<?php echo htmlspecialchars($element['audio_path']); ?>" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($element['video_path'])): ?>
                                <div class="video-player mt-3">
                                    <video controls width="100%">
                                        <source src="<?php echo htmlspecialchars($element['video_path']); ?>" type="video/mp4">
                                        Your browser does not support the video element.
                                    </video>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer class="bg-light mt-5 py-3">
        <div class="container text-center">
            <p class="mb-0">© 2025 Kids Learning Hub. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 