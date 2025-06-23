# GameExpress

A modern gaming news and review website built with PHP, MySQL, and Bootstrap. GameExpress provides a platform for gaming enthusiasts to read the latest news, reviews, and engage with the community through comments and discussions.

## Features

### For Users
- **Browse Gaming News**: Read the latest gaming industry news and updates
- **Game Reviews**: Access detailed game reviews with ratings and analysis
- **Search Functionality**: Search through posts by title, content, or category
- **User Registration & Authentication**: Create accounts and manage profiles
- **Comment System**: Engage with posts through threaded comments
- **User Preferences**: Customize sorting, ordering, and page size preferences
- **Responsive Design**: Optimized for desktop, tablet, and mobile devices

### For Authors
- **Content Management**: Create, edit, and manage articles
- **Image Upload**: Upload and manage post images and thumbnails
- **Rich Text Editor**: Use TinyMCE for advanced content formatting
- **Category Management**: Organize content by categories

### For Administrators
- **User Management**: Manage user accounts, roles, and permissions
- **Content Moderation**: Moderate comments and manage posts
- **Category Administration**: Create and manage content categories
- **Game Database**: Manage game information and categories
- **System Settings**: Configure site-wide settings and preferences

## Technology Stack

- **Backend**: PHP 8.2+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework**: Bootstrap 5
- **Rich Text Editor**: TinyMCE
- **Authentication**: Custom PHP session-based authentication
- **Image Processing**: PHP GD Library

## Installation

### 1. Clone the Repository
```bash
git clone https://github.com/stevenxu2/GameExpress.git
cd GameExpress
```

### 2. Database Setup
1. Create a new MySQL database named `game_express`
2. Import the database schema:
```bash
mysql -u root -p game_express < game_express.sql
```

### 3. Configure Database Connection
Edit `db_connection.php` and update the database credentials:
```php
define('DB_DSN', 'mysql:host=localhost;port=3306;dbname=game_express;charset=utf8');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### 4. Web Server Configuration
- Place the project files in your web server's document root
- Ensure the `uploads/` directory is writable by the web server
- Configure your web server to serve PHP files

## User Roles

### Administrator
- Full system access
- User management
- Content moderation
- System configuration

### Author
- Create and edit articles
- Manage own content
- Upload images
- Moderate comments on own posts

### User
- Browse content
- Comment on posts
- Manage preferences
- Search functionality

## License

This project is licensed under the MIT License - see the LICENSE file for details.