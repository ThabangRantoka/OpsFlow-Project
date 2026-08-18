# OpsFlow — Business Operations Management System (PHP + MySQL)

A role-based operations management system built with **PHP 8**, **MySQL/MariaDB** and vanilla CSS.
Runs on **XAMPP** out of the box.

## Modules

- Dashboard (live KPIs)
- Employees (CRUD, search, export)
- Departments
- Projects (budget, progress, priority)
- Attendance (daily register + reports)
- Leave management (request / approve / reject)
- Reports & CSV exports
- Users & roles (Admin only)
- Activity logs (Admin only)
- Notifications, Profile, Settings

## Installation (XAMPP)

1. Copy the whole `php-app` folder into `C:\xampp\htdocs\` and rename it to `opsflow`.
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. **If you already have the `opsflow` database in phpMyAdmin, skip this step** — the app
   matches your existing structure exactly. Otherwise open <http://localhost/phpmyadmin>,
   click **Import** → choose `database/opsflow.sql` → **Go**.
   The script is non-destructive: it uses `CREATE TABLE IF NOT EXISTS`, never drops
   anything and inserts no demo data.
4. Open <http://localhost/opsflow/>.

Database credentials are in `config/DataBase.php` (default XAMPP: user `root`, empty
password, database `opsflow`).

## Database structure used by the app

`users`, `employees`, `departments`, `projects`, `attendance`, `leave_requests`,
`notifications`, `activity_logs`, `clients`, `settings` — identical column names,
types and enum values to the existing `opsflow` database
(e.g. project status `Planning / In Progress / Completed / On Hold`,
priority `Low / Medium / High / Critical`, employee status `Active / Inactive / Leave`).

## Accounts

Your existing user accounts keep working — log in with the email and password already
stored in the `users` table. To add a new account open
<http://localhost/opsflow/Auth/register.php>.


On the login page you must pick **Admin**, **Manager** or **Employee** — the selected role
must match the role stored on the account, otherwise the login is rejected.

## Role permissions

| Area                         | Admin | Manager | Employee |
| ---------------------------- | ----- | ------- | -------- |
| Dashboard / Reports          | ✅    | ✅      | ✅       |
| Employees / Departments CRUD | ✅    | ✅      | ❌       |
| Projects CRUD                | ✅    | ✅      | ❌       |
| Attendance capture           | ✅    | ✅      | ❌       |
| Leave approval               | ✅    | ✅      | ❌       |
| Users & roles                | ✅    | ❌      | ❌       |
| Activity logs                | ✅    | ❌      | ❌       |

## Notes

- Passwords are stored with `password_hash()` (bcrypt) and verified with `password_verify()`.
- All database access uses prepared statements.
- Profile and user photos are uploaded to `Profile/photos/` and `Users/photos/` — keep those
  folders writable.
