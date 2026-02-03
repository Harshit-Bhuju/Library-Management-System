# Project File Structure

This document outlines the purpose of each file and directory in the Library Management System.

## Root Directory

| File              | Role                                                           |
| :---------------- | :------------------------------------------------------------- |
| `index.php`       | The main landing page / login portal for all users.            |
| `reset_admin.php` | Utility script to reset the admin password (highly sensitive). |
| `README.md`       | Standard project introduction and setup guide.                 |

## 📁 `config/`

Core system configurations.

- `config.php`: Global constants (Paths, URLs, Session settings).
- `db.php`: Database connection setup using PDO.

## 📁 `includes/`

Shared utility functions and UI components.

- `functions.php`: Security helpers, auth checks, and UI badge generators.
- `header.php`: Standard top navigation bar (Role-aware).
- `footer.php`: Standard page footer and core JS imports.

## 📁 `admin/`

Panel for librarians/administrators to manage data.

- `dashboard.php`: Statistical overview of the library.
- `manage_books.php`: Add, edit, or delete books.
- `manage_users.php`: Manage students and staff accounts.
- `manage_categories.php`: Categorization management.
- `issue_book.php`: Backend logic to lend a book.
- `return_book.php`: Interface to accept returned books and calculate fines.
- `view_issues.php`: Track all current borrowings and overdue items.

## 📁 `student/`

Portal for students to browse and request books.

- `dashboard.php`: View borrowed books, stats, and personalized recommendations.
- `browse_books.php`: Searchable catalog with filters and review options.
- `profile.php`: View and update personal information.
- `my_history.php`: Personal borrowing history and fine status.

## 📁 `api/`

Backend endpoints for AJAX/Frontend interactions.

- `get_reviews.php`: Fetches ratings for a specific book.
- `delete_review.php`: Dedicated endpoint for removing a review.
- `update_settings.php`: RESTful endpoint for theme persistence.

## 📁 `assets/`

Static resources.

- `css/main.css`: The primary design system and custom components.
- `js/main.js`: Core interactivity (Modals, Star Ratings, AJAX).
- `images/`: Default logos and static graphics.
