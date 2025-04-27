# Kids Learning Hub

An interactive educational platform for children aged 3-8, featuring various learning categories including Animals, Alphabets, Transport, Fruits, Colors, and Shapes.

## Features

- 🎨 Child-friendly interface with colorful design
- 📚 Multiple learning categories
- 🖼️ High-quality images
- 🔊 Audio pronunciations
- 📱 Responsive design for all devices
- 🔒 Secure admin panel
- 📝 Easy content management

## Installation

1. **Database Setup**
   ```sql
   -- Create database and tables
   mysql -u root -p < database/schema.sql
   
   -- Import initial data
   mysql -u root -p kids_learning < database/initial_data.sql
   ```

2. **File Setup**
   - Upload all files to your web server
   - Ensure the `uploads` directory is writable
     

3. **Configuration**
   - Update database credentials in `config/database.php` if needed
   - Default admin credentials:
     - Username: `admin`
     - Password: `admin123`

## Directory Structure

```
kids-learning/
├── admin/                        # Admin panel for managing content
│   ├── categories.php            # Manage categories (add/edit/delete)
│   ├── category.php              # Add/edit a single category
│   ├── dashboard.php             # Admin dashboard with stats and quick links
│   ├── elements.php              # Manage learning elements (add/edit/delete)
│   ├── get_elements.php          # AJAX endpoint to fetch elements by category
│   ├── index.php                 # Admin home page (lists categories)
│   ├── login.php                 # Admin login page
│   ├── logout.php                # Admin logout script
│   ├── media.php                 # Media library (upload/edit/delete media)
│   └── settings.php              # Admin settings (change password, system info)
│
── config/
│   └── database.php              # Database connection settings and function
│
├── database/
│   ├── initial_data.sql          # SQL file with initial data for categories/elements
│   └── schema.sql                # SQL file with database schema
├── uploads/                      # Media files
│   ├── animals/                  # Animal images and sounds
│   ├── alphabets/                # Alphabet images and sounds
│   ├── transport/                # Transport images and sounds
│   ├── fruits/                   # Fruit images and sounds
│   ├── colors/                   # Color images and sounds
│   └── shapes/                   # Shape images and sounds
├── index.php                     # Main landing page
├── category.php                  # Category view page
├── generate_audio.php            # Script to generate audio files for elements
├── generate_images.php           # Script to generate placeholder images for elements
├── index.php                     # Public home page
├── optimize_images.php           # Script to optimize images in /uploads
├── README.md                     # Project documentation and setup instructions
├── setup.php                     # Script to set up directories and sync files with DB
└── watch_uploads.php             # Script to sync /uploads directory with the database
```

## Usage

### Admin Panel
1. Access the admin panel at `/admin/login.php`
2. Log in with admin credentials
3. Manage categories and learning elements
4. Upload new content (images, audio, video)

### Public Interface
1. Browse categories on the home page
2. Click on a category to view its elements
3. Interact with learning materials (images, audio, video)

## Security

- All passwords are hashed using PHP's `password_hash()`
- SQL injection prevention using prepared statements
- Input validation and sanitization
- Session-based authentication
- Secure file upload handling

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- File upload permissions
- GD library for image processing

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request
