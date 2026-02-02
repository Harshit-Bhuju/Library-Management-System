# Library Management System

A production-ready Library Management System built with Core PHP, MySQL, and Vanilla JavaScript.

## Features

- **Admin Panel**: Manage books, students, issue/return books, dashboard stats.
- **Student Panel**: View profiles, borrow history, browse books (search/filter).
- **Authentication**: Secure Login/Registration with password hashing.
- **Modern UI**: Response design, Dark/Light mode, Animations.

## Setup Instructions

### 1. Database Setup

1. Open your MySQL tool (phpMyAdmin, Workbench, etc.).
2. Create time database `library_system` (if not autocreated).
3. Import the `database_setup.sql` file located in the root directory.
   - This will create all tables and a default Admin account.

### 2. Configuration

1. Open `config/db.php` and `config/config.php`.
2. Update `DB_PASS` if your MySQL root password is not empty.
3. Update `BASE_URL` in `config/config.php` if your project folder is not named `library-management-system`. The host/domain is detected automatically.

### 3. Usage

- **Admin Login**:
  - Email: `admin@library.com`
  - Password: `admin123`
- **Student**:
  - Register a new account from the Login page.

## Folder Structure

- `admin/`: Admin pages (Dashboard, Manage Books, etc.)
- `student/`: Student pages (Dashboard, Profile, etc.)
- `auth/`: Login, Register, Logout
- `config/`: Database and App config
- `includes/`: Reusable header, footer, sidebar, functions
- `assets/`: CSS, JS, Images
