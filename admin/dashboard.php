<?php
session_start();
require_once '../config/database.php';

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$conn = getDBConnection();

// Get statistics
$categories = $conn->query("SELECT COUNT(*) as count FROM categories")->fetch_assoc()['count'];
$elements = $conn->query("SELECT COUNT(*) as count FROM elements")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Kids Learning Hub</title>
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
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
        }
        .stat-number {
            font-size: 2.5rem;
            color: #ff6b6b;
            font-weight: bold;
        }
        .stat-label {
            color: #666;
            font-size: 1.1rem;
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
            <a class="navbar-brand" href="../index.php">Kids Learning Hub</a>
            <div class="ms-auto">
                <a href="logout.php" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center mb-5">Admin Dashboard</h1>
        
        <div class="row mb-5">
            <div class="col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $categories; ?></div>
                    <div class="stat-label">Categories</div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $elements; ?></div>
                    <div class="stat-label">Learning Elements</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Manage Categories</h5>
                        <p class="card-text">Add, edit, or remove learning categories.</p>
                        <a href="categories.php" class="btn btn-primary">Manage Categories</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Manage Elements</h5>
                        <p class="card-text">Add, edit, or remove learning elements.</p>
                        <a href="elements.php" class="btn btn-primary">Manage Elements</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Media Library</h5>
                        <p class="card-text">Manage images, audio, and video files.</p>
                        <a href="media.php" class="btn btn-primary">Media Library</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Settings</h5>
                        <p class="card-text">Configure system settings and preferences.</p>
                        <a href="settings.php" class="btn btn-primary">Settings</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Upload Directory Sync</h5>
                        <p class="card-text">Synchronize files from upload directory with database.</p>
                        <button id="syncButton" class="btn btn-primary">
                            <i class="bi bi-arrow-repeat"></i> Sync Now
                        </button>
                        <div id="syncMessages" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('syncButton').addEventListener('click', function() {
            const button = this;
            const messagesDiv = document.getElementById('syncMessages');
            
            button.disabled = true;
            button.innerHTML = '<i class="bi bi-arrow-repeat"></i> Syncing...';
            messagesDiv.innerHTML = '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

            fetch('../watch_uploads.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'sync=1'
            })
            .then(response => response.json())
            .then(data => {
                if(data.messages.length === 0) {
                    messagesDiv.innerHTML = '<div class="alert alert-info">No changes detected.</div>';
                } else {
                    let html = '<div class="alert alert-success">';
                    html += '<h6 class="alert-heading">Sync Results:</h6><ul class="mb-0">';
                    data.messages.forEach(message => {
                        html += `<li>${message}</li>`;
                    });
                    html += '</ul></div>';
                    messagesDiv.innerHTML = html;
                }
            })
            .catch(error => {
                messagesDiv.innerHTML = '<div class="alert alert-danger">Error during sync. Please try again.</div>';
                console.error('Sync error:', error);
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = '<i class="bi bi-arrow-repeat"></i> Sync Now';
            });
        });
    </script>
</body>
</html>