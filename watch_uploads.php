<?php
require_once 'config/database.php';

class MediaSync {
    private $conn;
    private $uploads_dir;
    private $messages = [];

    public function __construct() {
        $this->conn = getDBConnection();
        $this->uploads_dir = __DIR__ . '/uploads';
    }

    private function scanDirectory($dir, &$results = array()) {
        $files = scandir($dir);
        
        foreach($files as $file) {
            if($file === '.' || $file === '..') continue;
            
            $path = $dir . '/' . $file;
            if(is_dir($path)) {
                $this->scanDirectory($path, $results);
            } else {
                $category = basename(dirname($path));
                $results[] = [
                    'path' => $path,
                    'category' => $category,
                    'filename' => $file
                ];
            }
        }
        
        return $results;
    }

    private function getFileType($filename) {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        $types = [
            'image' => ['jpg', 'jpeg', 'png', 'gif'],
            'audio' => ['mp3', 'wav'],
            'video' => ['mp4', 'webm']
        ];

        foreach ($types as $type => $extensions) {
            if (in_array($extension, $extensions)) {
                return $type;
            }
        }
        
        return null;
    }

    private function addMessage($message) {
        $this->messages[] = $message;
    }

    public function sync() {
        $files = [];
        $this->scanDirectory($this->uploads_dir, $files);
        
        foreach($files as $file) {
            $this->processFile($file);
        }
        
        $this->checkDeletedFiles();
        $this->conn->close();
        
        return $this->messages;
    }

    private function processFile($file) {
        $relative_path = str_replace(__DIR__ . '/', '', $file['path']);
        $media_type = $this->getFileType($file['filename']);
        
        if(!$media_type) return;
        
        $existing = $this->checkExistingFile($relative_path);
        
        if(!$existing) {
            $this->addNewFile($file, $relative_path, $media_type);
        } elseif($existing['status'] === 'deleted') {
            $this->reactivateFile($existing['id'], $relative_path);
        }
    }

    private function checkExistingFile($relative_path) {
        $stmt = $this->conn->prepare("
            SELECT id, status 
            FROM media_elements 
            WHERE file_path = ?
        ");
        $stmt->bind_param("s", $relative_path);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    private function addNewFile($file, $relative_path, $media_type) {
        $category = $this->getCategoryId($file['category']);
        
        if(!$category) return;

        $element_id = $this->createElement($category['id'], $file['filename']);
        $this->insertMediaElement($element_id, $relative_path, $media_type);
        $this->addMessage("Added new file: $relative_path");
    }

    private function getCategoryId($category_name) {
        $stmt = $this->conn->prepare("SELECT id FROM categories WHERE name = ?");
        $stmt->bind_param("s", $category_name);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    private function createElement($category_id, $filename) {
        $element_name = pathinfo($filename, PATHINFO_FILENAME);

        // Check if element already exists
        $stmt = $this->conn->prepare("SELECT id FROM elements WHERE category_id = ? AND name = ?");
        $stmt->bind_param("is", $category_id, $element_name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['id'];
        }

        // Insert new element if not exists
        $stmt = $this->conn->prepare("INSERT INTO elements (category_id, name) VALUES (?, ?)");
        $stmt->bind_param("is", $category_id, $element_name);
        $stmt->execute();
        return $stmt->insert_id;
    }

    private function insertMediaElement($element_id, $relative_path, $media_type) {
        $stmt = $this->conn->prepare("
            INSERT INTO media_elements (element_id, file_path, media_type, status) 
            VALUES (?, ?, ?, 'active')
        ");
        $stmt->bind_param("iss", $element_id, $relative_path, $media_type);
        $stmt->execute();
    }

    private function reactivateFile($id, $relative_path) {
        $stmt = $this->conn->prepare("
            UPDATE media_elements 
            SET status = 'active' 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $this->addMessage("Reactivated file: $relative_path");
    }

    private function checkDeletedFiles() {
        $stmt = $this->conn->prepare("
            SELECT id, file_path 
            FROM media_elements 
            WHERE status = 'active'
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        
        while($row = $result->fetch_assoc()) {
            if(!file_exists(__DIR__ . '/' . $row['file_path'])) {
                $this->markFileAsDeleted($row['id'], $row['file_path']);
            }
        }
    }

    private function markFileAsDeleted($id, $file_path) {
        $stmt = $this->conn->prepare("
            UPDATE media_elements 
            SET status = 'deleted' 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $this->addMessage("Marked as deleted: $file_path");
    }
}

// Handle AJAX request
if(isset($_POST['sync'])) {
    $syncer = new MediaSync();
    $messages = $syncer->sync();
    echo json_encode(['messages' => $messages]);
    exit;
}