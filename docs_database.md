# Database Documentation

This document describes the database schema for the Library Management System.

## Tables Overview

| Table Name        | Description                                                      |
| :---------------- | :--------------------------------------------------------------- |
| `users`           | Stores information about all users (Admins, Teachers, Students). |
| `books`           | Contains the catalog of books available in the library.          |
| `categories`      | Defines the genres/categories for books.                         |
| `issued_books`    | Tracks book borrowings, return status, and fines.                |
| `reviews`         | Stores book ratings and comments from students.                  |
| `notifications`   | System-generated alerts for users (due dates, arrivals).         |
| `activity_log`    | Audit trail of all major actions performed in the system.        |
| `system_settings` | Global configuration values (e.g., borrow limits, fine rates).   |
| `user_settings`   | Individual user preferences (e.g., theme choice).                |

---

## Detailed Table Structure

### 1. `users`

Stores user credentials and profile details.

- `user_id` (INT, PK): Unique identifier.
- `roll_no` (VARCHAR): Student/Staff ID (Unique).
- `name` (VARCHAR): Full name.
- `email` (VARCHAR): Email address (Unique).
- `password` (VARCHAR): Hashed password.
- `role` (ENUM): 'admin', 'teacher', 'student'.
- `department` (VARCHAR): Department or Class.
- `year` (INT): Batch or Year.
- `phone` (VARCHAR): Contact number.
- `address` (TEXT): Physical address.
- `profile_image` (VARCHAR): Path to avatar image.
- `status` (ENUM): 'active', 'inactive'.

### 2. `books`

The library's inventory.

- `book_id` (INT, PK): Unique identifier.
- `isbn` (VARCHAR): International Standard Book Number.
- `title` (VARCHAR): Book name.
- `author` (VARCHAR): Author name.
- `publisher` (VARCHAR): Publishing house.
- `publication_year` (YEAR): Year of release.
- `category_id` (INT, FK): Link to `categories`.
- `description` (TEXT): Book summary.
- `total_copies` (INT): Stock count.
- `available_copies` (INT): Current stock for borrowing.
- `shelf_location` (VARCHAR): Physical position in library.
- `cover_image` (VARCHAR): Path to cover art.
- `is_active` (TINYINT): Visibility status (1 = visible, 0 = hidden).

### 3. `issued_books`

Transaction history for borrowing books.

- `issue_id` (INT, PK): Unique identifier.
- `book_id` (INT, FK): Link to `books`.
- `user_id` (INT, FK): Link to `users`.
- `issue_date` (DATE): Date borrowed.
- `due_date` (DATE): Deadline for return.
- `return_date` (DATE): Actual return date.
- `fine_amount` (DECIMAL): Penalty for late returns.
- `status` (ENUM): 'issued', 'returned', 'overdue', 'requested', 'cancelled'.
- `issued_by` (INT, FK): Admin/Staff who handled the issue.

### 4. `reviews`

Student feedback system.

- `review_id` (INT, PK): Unique identifier.
- `book_id` (INT, FK): Link to `books`.
- `user_id` (INT, FK): Link to student.
- `rating` (INT): Star count (1-5).
- `review_text` (TEXT): Written feedback.
