# Features List

The Library Management System provides a comprehensive suite of features for both administration and student usage.

## 👥 Student Portal Features

- **Personal Dashboard**: Real-time stats of borrowed books, read count, and total fines.
- **Smart Recommendations**: A personalized section suggesting books based on your top-read categories and high-rated interests.
- **Interactive Library**:
  - Dynamic filtering by search, category, and availability.
  - View book details and status labels (Active/Inactive/Out of Stock).
- **One-Click Requests**: Request books directly from the browse page.
- **Review System**:
  - Rate books (1-5 stars) with interactive UI.
  - Write and update reviews.
  - Delete own reviews.
- **Profile Management**: Update contact details and track class/year information.
- **Theme Persistence**: Switch between light and dark modes; your preference is remembered via the database.

## 🛡️ Admin Panel Features

- **Centralized Dashboard**: Live counters for total books, active students, issued books, and total revenue from fines.
- **Inventory Management**:
  - Full CRUD for books (Add/Edit/Delete).
  - Toggle book visibility (Activate/Deactivate).
  - Track stock levels automatically as books are issued.
- **Circulation Control**:
  - Issue books to students with custom due dates.
  - Process returns and calculate late fines automatically.
  - View and filter all borrowing transactions.
- **User Management**: Add new students/staff and manage their account status.
- **Category Management**: Organize the library into custom genres for better navigation.
- **Audit Logging**: A detailed activity log tracking every major action (Logins, Book edits, Issues) with IP addresses for security.

## ⚙️ Technical Features

- **Responsive Design**: Fully optimized for mobile, tablet, and desktop using a custom CSS framework.
- **Security**:
  - CSRF protection on all forms.
  - Input sanitization and PDO prepared statements for SQL injection prevention.
  - Role-based Access Control (RBAC).
- **Fast UI**: Heavy use of AJAX for reviews and settings to ensure smooth navigation without page reloads.
