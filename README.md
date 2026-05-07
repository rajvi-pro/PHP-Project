# Internshop
# 🎓 Internship Management System

A comprehensive web-based application built with PHP for managing student internships, applications, and administrative tasks.

## 📋 Table of Contents
- [Features](#features)
- [Technologies Used](#technologies-used)
- [System Requirements](#system-requirements)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Configuration](#configuration)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [Screenshots](#screenshots)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)

## ✨ Features

### Student Features
- 📝 Student registration and login
- 🔐 Secure password management and reset
- 👤 Profile management and updates
- 📄 Internship application submission
- 📊 Personal dashboard with application status
- 📧 Email notifications
- 📎 Resume upload and management

### Admin Features
- 👥 View and manage student applications
- 📈 Track internship applications
- 🔍 Search and filter applications
- ✅ Approve/reject applications
- 📊 Generate reports
- 🗓️ Team diary management

### Additional Features
- 📱 Responsive design
- 🔒 Secure authentication system
- 📧 OTP verification
- 📑 Terms and conditions management

## 🛠️ Technologies Used

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript
- **Server**: Apache (XAMPP/WAMP/LAMP)

## 💻 System Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache Web Server
- Web Browser (Chrome, Firefox, Safari, Edge)
- Minimum 2GB RAM
- 500MB free disk space

## 📥 Installation

### Step 1: Clone the Repository
```bash
git clone https://github.com/rajvi-pro/PHP-Project.git
cd PHP-Project
```

### Step 2: Install XAMPP/WAMP
1. Download XAMPP from [https://www.apachefriends.org](https://www.apachefriends.org)
2. Install XAMPP on your system
3. Start Apache and MySQL services

### Step 3: Move Project to Server Directory
- **For XAMPP**: Copy project folder to `C:\xampp\htdocs\`
- **For WAMP**: Copy project folder to `C:\wamp64\www\`
- **For LAMP**: Copy project folder to `/var/www/html/`

### Step 4: Create Database
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Create a new database named `internship_management`
3. Import the SQL file (if provided) or create tables manually

## 🗄️ Database Setup

### Create Database
```sql
CREATE DATABASE internshop_db;
USE internshop_db;
```

### Required Tables
The system requires the following tables:
- `students` - Student information
- `applications` - Internship applications
- `internships` - Available internships
- `admin` - Admin users
- `team_diary` - Team activity logs

### Import SQL File (if available)
```bash
mysql -u root -p internshop_db< database.sql
```

## ⚙️ Configuration

### Step 1: Configure Database Connection
Create/edit `config.php` or `database.php`:

```php
<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'internshop_db');

// Create Connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check Connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
```

### Step 2: Configure Email Settings (if applicable)
Edit email configuration in relevant PHP files:

```php
$smtp_host = 'smtp.gmail.com';
$smtp_port = 587;
$smtp_user = 'your-email@gmail.com';
$smtp_pass = 'your-app-password';
```

### Step 3: Set File Upload Directory
Ensure the `uploads/` directory has write permissions:

```bash
chmod 755 uploads/
```

## 🚀 Usage

### Accessing the Application
1. Open your web browser
2. Navigate to: `http://localhost/Internship-Management-System/`

### Student Login
1. Go to `student_login.php`
2. Register a new account using `student_register.php`
3. Login with your credentials
4. Access your dashboard at `student_dashboard.php`

### Admin Login
1. Go to admin login page
2. Use admin credentials
3. Access admin dashboard

### Common URLs
- **Home**: `http://localhost/Internship-Management-System/index.php`
- **Student Register**: `http://localhost/Internship-Management-System/student_register.php`
- **Student Login**: `http://localhost/Internship-Management-System/student_login.php`
- **Student Dashboard**: `http://localhost/Internship-Management-System/student_dashboard.php`

## 📁 Project Structure
