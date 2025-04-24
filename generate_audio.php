<?php
require_once 'config/database.php';

$conn = getDBConnection();

// Get all elements
$elements = $conn->query("SELECT * FROM elements");

// Directory for audio files
$audio_dir = 'uploads/audio';
if (!file_exists($audio_dir)) {
    mkdir($audio_dir, 0777, true);
}

// Function to generate audio using Google Text-to-Speech
function generateAudio($text, $filename) {
    $url = "http://translate.google.com/translate_tts?ie=UTF-8&client=tw-ob&q=" . urlencode($text) . "&tl=en";
    $audio = file_get_contents($url);
    
    if ($audio !== false) {
        file_put_contents($filename, $audio);
        return true;
    }
    return false;
}

// Process each element
while ($element = $elements->fetch_assoc()) {
    $audio_path = $audio_dir . '/' . strtolower($element['name']) . '.mp3';
    
    if (!file_exists($audio_path)) {
        echo "Generating audio for: " . $element['name'] . "\n";
        
        // Generate audio for the name
        if (generateAudio($element['name'], $audio_path)) {
            // Update database with audio path
            $stmt = $conn->prepare("UPDATE elements SET audio_path = ? WHERE id = ?");
            $stmt->bind_param("si", $audio_path, $element['id']);
            $stmt->execute();
            
            echo "Successfully generated audio for: " . $element['name'] . "\n";
        } else {
            echo "Failed to generate audio for: " . $element['name'] . "\n";
        }
        
        // Add a small delay to avoid rate limiting
        sleep(1);
    }
}

echo "Audio generation completed!";
?> 