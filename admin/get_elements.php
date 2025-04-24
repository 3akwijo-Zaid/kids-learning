<?php
require_once '../config/database.php';

$category_id = $_GET['category_id'] ?? 0;
$conn = getDBConnection();

$elements = [];
if ($category_id > 0) {
    $stmt = $conn->prepare("SELECT id, name FROM elements WHERE category_id = ? ORDER BY name");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $elements[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($elements);