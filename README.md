# 📚 Library Management System

A high-performance, production-ready Library Management System built with **Core PHP**, **MySQL**, and **Vanilla JavaScript**. This system features a dual-portal design for Administrators and Students, focusing on aesthetics, speed, and personalized user experience.

---

## ✨ Key Features

### 🛡️ Administrative Power

- **Inventory Control**: Full CRUD for books and categories with visibility toggles.
- **Circulation Management**: Seamless issue and return tracking with automated fine calculation.
- **User Auditing**: Management of student/staff accounts and a detailed activity log.

### 👥 Student Experience

- **Smart Recommendations**: Personalized book suggestions based on reading history and favorite categories.
- **Interactive Browsing**: Advanced filtering (Search, Category, Availability) with live status labels.
- **Review System**: Ability to rate, review, update, and manage feedback on books.
- **Modern UI**: Light/Dark mode persistence and fluid CSS animations.

---

## 🚀 Quick Setup

### 1. Database Initialization

1. Create a database named `library_system` in your MySQL server.
2. Import **`database.sql`** from the root directory to set up the tables and sample data.

### 2. Configuration

1. Setup your database credentials in `config/db.php`.
2. check `config/config.php` to ensure the `BASE_URL` matches your local environment.

### 3. Default Credentials

- **Admin**: `admin@library.com` / `admin123`
- **Student**: Register a new account directly from the entry portal.

---

## 📖 Detailed Documentation

For deep technical details, please refer to the following files:

- 🏛️ **[Database Schema](docs_database.md)**: Detailed table structures and relationships.
- 📁 **[Project Structure](docs_files.md)**: Understanding the role of every file and folder.
- 🌟 **[Full Feature Set](docs_features.md)**: A complete list of all implemented functionalities.

---

## 🛠️ Technology Stack

- **Backend**: PHP 8.x (PDO for DB interactions)
- **Frontend**: Vanilla JS (ES6+), Vanilla CSS (Custom UI Framework)
- **Database**: MySQL / MariaDB
- **Security**: CSRF Protection, Password Hashing, Input Sanitization
