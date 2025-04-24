-- Create the database
CREATE DATABASE IF NOT EXISTS kids_learning;
USE kids_learning;

-- Create admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create elements table
CREATE TABLE IF NOT EXISTS elements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image_path VARCHAR(255),
    audio_path VARCHAR(255),
    video_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Create media_elements table
CREATE TABLE IF NOT EXISTS media_elements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    element_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    media_type ENUM('image', 'audio', 'video') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (element_id) REFERENCES elements(id) ON DELETE CASCADE
);

-- Insert default admin user (password: admin123)
INSERT INTO admins (username, password_hash) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');