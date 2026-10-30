# Student Management System

A small CRUD web app for managing student records, built as a university DBMS
project. Users can register, log in, and then add, view, edit, and delete
student details (including a photo upload).

## Tech stack

- **PHP** (procedural, with `mysqli` and prepared statements)
- **MySQL** (the schema lives in `schema.sql`)
- **Bootstrap 4.6** for the UI
- **Font Awesome 4.7** for icons
- **jQuery 3.6** for the sidebar toggle

## Project structure

```
.
├── index.php                   # Register + Login page
├── main.php                    # Dashboard (requires login)
├── add_student.php             # Create a student record
├── view_student.php            # List all students
├── edit_student_detail.php     # Edit a single student
├── delete_student_detail.php   # Delete a single student
├── logout.php                  # Destroy the session
├── dbconnect.php               # Opens the mysqli connection from config.php
├── config.example.php          # Template — copy to config.php and fill in creds
├── config.php                  # Real credentials (git-ignored)
├── schema.sql                  # CREATE DATABASE + CREATE TABLE statements
├── style.css                   # Custom styles
├── images/                     # Static images used in the layout
└── st_image/                   # Uploaded student photos (git-ignored)
```

## Database schema

Two tables, both defined in `schema.sql`:

**`register`** — login accounts.

| Column     | Type           | Notes                          |
|------------|----------------|--------------------------------|
| st_id      | INT, PK, AI    | Account id                     |
| fname      | VARCHAR(100)   | Full name                      |
| email      | VARCHAR(150)   | Unique, used as the login id   |
| password   | VARCHAR(255)   | bcrypt hash (never plaintext)  |
| cpassword  | VARCHAR(255)   | Legacy "confirm password" hash |

**`student_detail`** — one row per student.

| Column      | Type         | Notes                       |
|-------------|--------------|-----------------------------|
| st_id       | INT, PK, AI  | Student id                  |
| fname       | VARCHAR(100) | First name                  |
| lname       | VARCHAR(100) | Last name                   |
| father_name | VARCHAR(100) |                             |
| email       | VARCHAR(150) |                             |
| mobile      | VARCHAR(20)  |                             |
| gender      | VARCHAR(10)  | `male` / `female`           |
| district    | VARCHAR(100) |                             |
| city        | VARCHAR(100) |                             |
| state       | VARCHAR(100) |                             |
| nation      | VARCHAR(100) | Nationality                 |
| photo       | VARCHAR(255) | Filename inside `st_image/` |

## Setup (XAMPP / WAMP / MAMP)

1. **Clone the project into your web root.**
   On XAMPP that's typically `C:\xampp\htdocs\student-management-system`.
2. **Start Apache and MySQL** from the XAMPP control panel.
3. **Import the schema.** Open <http://localhost/phpmyadmin>, click *Import*,
   choose `schema.sql`, and run it. This creates the `student_db` database and
   both tables.
4. **Create the local config file.**
   ```bash
   cp config.example.php config.php
   ```
   Open `config.php` and fill in your MySQL host, user, password, and database
   name. The default XAMPP values (`localhost`, `root`, empty password,
   `student_db`) already match the template.
5. **Visit the app** at <http://localhost/student-management-system/index.php>.
6. **Register** a new account, then log in. You'll land on the dashboard.

## Security notes (what was hardened, and why)

- All queries that take user input use **`mysqli` prepared statements** with
  bound parameters. This prevents SQL injection by separating SQL from data.
- Passwords are stored using **`password_hash()` (bcrypt)** and verified with
  **`password_verify()`**. Plaintext passwords are never written to the DB.
- Every protected page calls `header('Location: index.php'); exit;` when the
  session isn't logged in, so the rest of the page never executes for an
  unauthenticated visitor.
- DB credentials live in `config.php`, which is **git-ignored**. Only
  `config.example.php` (with placeholders) is committed.
- The image upload accepts only `image/jpg`, `image/jpeg`, and `image/png`,
  and rejects anything larger than **2 MB**.
- Output read from the database is escaped with `htmlspecialchars()` when
  echoed back into HTML, reducing the XSS surface.

## Known limitations

This is a learning project. A production-grade rewrite would also:

- Use CSRF tokens on every form.
- Validate that uploaded files are *actually* images (e.g. `getimagesize()` or
  `finfo`) instead of trusting the browser-supplied MIME type.
- Rename uploaded files to a random/safe name to avoid path traversal and
  filename collisions.
- Replace the `cpassword` column with client-side confirmation only.
- Add proper error pages instead of inline `die()` messages.
