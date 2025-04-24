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
   - Run the setup script to create directories and download media:
     ```bash
     php setup.php
     ```

3. **Configuration**
   - Update database credentials in `config/database.php` if needed
   - Default admin credentials:
     - Username: `admin`
     - Password: `admin123`

## Directory Structure

```
kids-learning/
├── admin/              # Admin panel files
├── config/             # Configuration files
├── database/           # Database schema and initial data
├── uploads/            # Media files
│   ├── animals/        # Animal images and sounds
│   ├── alphabets/      # Alphabet images and sounds
│   ├── transport/      # Transport images and sounds
│   ├── fruits/         # Fruit images and sounds
│   ├── colors/         # Color images and sounds
│   └── shapes/         # Shape images and sounds
├── index.php           # Main landing page
├── category.php        # Category view page
└── setup.php           # Setup script
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

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please contact [your-email@example.com] 