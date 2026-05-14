# 🛠️ UniFix — University Problem Reporting System

![PHP](https://img.shields.io/badge/Backend-PHP%208-blue)
![MySQL](https://img.shields.io/badge/Database-MySQL-orange)
![Bootstrap](https://img.shields.io/badge/UI-Bootstrap%205.3-purple)
![License](https://img.shields.io/badge/License-MIT-green)

UniFix is a full-stack, web-based issue management platform built for Ethiopian universities. It enables students and teachers to report campus problems, administrators to review and route tickets, and specialist staff (Solvers) to resolve them — all in one unified system with real-time notifications, ticket chat, and SLA tracking.

---

## 🌐 Live Demo

🔗 [http://unifix.wuaze.com/](http://unifix.wuaze.com/)

---

## 📸 Screenshots

### 🔑 Login
| Login Interface |
| :---: |
| <img src="https://github.com/yonasleykun27/UniFix/blob/main/Img/1.png?raw=true" width="600"> |

### 👨‍🎓 Student Dashboard
| Report Submission | Issue Tracking |
| :---: | :---: |
| <img src="https://github.com/yonasleykun27/UniFix/blob/main/Img/3.png?raw=true" width="300"> |

### 🛡️ Admin Portal
| System Management | User Database |
| :---: | :---: |
| <img src="https://github.com/yonasleykun27/UniFix-System/blob/main/Img/Admin%20Page%201.png?raw=true" width="400"> | <img src="https://github.com/yonasleykun27/UniFix-System/blob/main/Img/Admin%20Page%202.png?raw=true" width="400"> |

---

## ✨ Features

### 👨‍🎓 For Students
- Submit campus issue reports across multiple categories (Dormitory, Cafeteria, Technology, Work Environment, Academic, etc.)
- Dynamic form fields that adapt based on the selected category (e.g. block/room for dormitory, lab/device for technology)
- Attach photo evidence to reports (supports jpg, jpeg, png, gif, webp)
- Track real-time status of all submitted reports: **Pending → Assigned → In Progress → Finished / Declined**
- View full report history and soft-delete reports from personal view
- Edit pending reports before admin review
- Real-time barcode scanning via **QuaggaJS** to verify university ID during registration
- Mobile camera support for ID photo capture on smartphones
- Receive in-app and email notifications at every status change
- Engage in **Ticket Chat** — message the assigned solver directly within the ticket

### 👩‍🏫 For Teachers
- Submit reports for lab issues, work environment problems, facility concerns, and administrative matters
- Same real-time tracking and notification system as students
- Access full submission history and ticket chat

### 🛡️ For Administrators
- Review all incoming pending reports assigned by the round-robin engine
- **Assign** reports to the correct specialist job title (ICT Manager, General Technician, Cafeteria Manager, etc.)
- Automated **Round-Robin assignment algorithm** — evenly distributes workload among admins and solvers
- **Decline** reports with a mandatory reason (triggers email to reporter)
- Full **User Management**: view all students, teachers, and solvers in a searchable database
- **Issue Warnings** to users — 3 warnings automatically bans the account
- **Retract Warnings** to unban or reduce an unfair penalty
- **Unban** accounts manually from the admin panel
- **Add Staff** (new Solver or Admin accounts) directly from the dashboard
- **Delete Users** with cascade — removing a user also removes all their reports and notifications
- **Contact Info Editor** — update email and phone for any user
- **SLA Management** — set a deadline (hours) for any ticket; breached tickets trigger automatic email escalation alerts
- **Analytics Dashboard** — charts for category distribution, status breakdown, and solver performance
- **Help Request Inbox** — view and reply to messages sent from the public help form

### 🔧 For Solvers (Staff)
- View only the tasks assigned to their specific job title
- Mark tasks as **In Progress** or **Finished**
- **Delegate** tasks to another eligible solver with an optional note
- Accept or decline incoming delegated tickets
- **Leave toggle** — mark yourself as on leave so no new tasks are assigned during absence
- Engage in Ticket Chat to communicate with reporters and admins
- View full job history (past finished/declined tasks)

---

## 🔔 Notification System

UniFix has a dual-layer notification system:

| Layer | Description |
|-------|-------------|
| **In-App (Push)** | Real-time bell icon with unread count, dropdown list, and auto-refresh every 30 seconds |
| **Email (SMTP)** | Branded HTML emails sent via Gmail SMTP (PHPMailer) for key events |

**Email triggers:**
- New report submitted → Admin notified
- Report assigned → Reporter & Solver notified
- Report In Progress → Reporter notified
- Report Finished → Reporter & Admin notified
- Report Declined → Reporter notified with reason
- Task Delegated → New solver notified
- Warning issued → User notified
- SLA deadline breached → Admin escalation alert

All email sends are logged to `uploads/email_logs/notifications.log`.

---

## 💬 Ticket Chat

Each report has a dedicated real-time message thread between:
- The reporter (student/teacher)
- The assigned solver
- Admins

Features:
- Send, edit, and delete messages
- System messages auto-posted for delegation events (accept/decline)
- Message count badge visible on each report card

---

## 🔐 Security Features

| Feature | Details |
|---------|---------|
| **Session Authentication** | PHP sessions guard all protected API endpoints |
| **Per-User Brute-Force Protection** | 5 failed attempts → 15-minute lockout; 2 lockouts → permanent ban |
| **Password Hashing** | bcrypt via `password_hash()`; auto-upgrades legacy plain-text passwords on login |
| **Role Verification** | Username prefix enforced against DB role on every login |
| **SQL Injection Prevention** | All queries use PDO prepared statements |
| **Column Whitelisting** | `updateContent` action only allows safe DB columns to be updated |
| **Forgot Password** | Tokenised reset link sent to user's registered email via SMTP |
| **Change Password** | Authenticated endpoint to update password from within the dashboard |

---

## 🏗️ System Architecture

```
Browser (HTML/CSS/JS)
        ↕ fetch() / FormData
PHP API Endpoints (manage_reports.php, manage_users.php, etc.)
        ↕ PDO Prepared Statements
MySQL Database (unifix_db)
        ↕
Email Notifications (PHPMailer → Gmail SMTP)
```

---

## 🧠 Round-Robin Assignment Logic

When a report is submitted:
1. All active Admin accounts are sorted alphabetically.
2. The admin index is calculated as `total_reports_ever % admin_count`.
3. The report is pre-assigned to that admin for review.

When an admin approves and assigns the report:
1. The system finds all eligible Solvers for the required job title.
2. Solvers on leave or banned are excluded.
3. The solver index is calculated as `reports_in_role % eligible_solver_count`.
4. If no specialist is found, the system falls back to a **Staff General Technician**.

---

## 🗄️ Database Schema

| Table | Purpose |
|-------|---------|
| `users` | All accounts: Students, Teachers, Admins, Solvers |
| `reports` | Issue tickets with status, assignment, and delegation fields |
| `ticket_messages` | Per-ticket chat messages |
| `notifications` | In-app push notifications per user |
| `help_requests` | Public contact/help form submissions |
| `login_attempts` | Per-username brute-force tracking |
| `password_resets` | One-time tokens for password reset flow |

---

## 🚀 Tech Stack

| Layer | Technology |
|-------|-----------|
| Frontend | HTML5, CSS3 (Glassmorphism UI), Bootstrap 5.3, Vanilla JS |
| Backend | PHP 8 |
| Database | MySQL (via PDO) |
| Email | PHPMailer + Gmail SMTP |
| Barcode Scanning | QuaggaJS |
| Icons | Bootstrap Icons |
| Fonts | Google Fonts — Inter |

---

## 📂 Project Structure

```
UniFix/
├── index.html                # Landing page & login modal
├── register_student.html     # Student registration with ID scan
├── register_teacher.html     # Teacher/staff registration
├── reset_password.html       # Password reset page (token-based)
├── student_dashboard.html    # Student portal
├── teacher_dashboard.html    # Teacher portal
├── admin_dashboard.html      # Admin management portal
├── solver_dashboard.html     # Solver/staff task dashboard
│
├── login.php                 # Authentication + brute-force protection
├── logout.php                # Session destruction
├── register.php              # Account creation API
├── get_data.php              # Fetch all users and reports (authenticated)
├── manage_reports.php        # Submit, update, delete, delegate, SLA actions
├── manage_users.php          # Warn, ban, delete, leave, contact update actions
├── ticket_chat.php           # Per-ticket chat CRUD
├── notifications.php         # In-app notification fetch/mark-read/push
├── push_notification.php     # Helper: write notifications to DB
├── notify.php                # Email notification service (PHPMailer)
├── forgot_password.php       # Send password reset email
├── reset_password_api.php    # Validate token and update password
├── change_password.php       # Authenticated password change
├── send_help.php             # Public help/contact form handler
├── setup_database.php        # One-time DB setup and seeding script
├── auth_check.php            # Session auth middleware
├── db_connect.php            # PDO database connection
│
├── css/style.css             # Global styles
├── js/main.js                # Core JS engine: System object, translations, logic
├── PHPMailer/                # PHPMailer library
└── Img/                      # Assets, favicons, screenshots
```

---

## ⚙️ Local Setup (XAMPP)

1. Clone the repository into your XAMPP `htdocs` folder:
   ```bash
   git clone https://github.com/yonasleykun27/UniFix-System.git C:/xampp/htdocs/UniFix
   ```

2. Start **Apache** and **MySQL** from the XAMPP Control Panel.

3. Run the automated database setup and patcher by visiting:
   ```
   http://localhost/UniFix/setup_database.php
   ```
   > **Note:** If you are migrating an older `unifix_db.sql` file to a new PC, running this script will automatically **patch your database**, adding any newly introduced columns (like `email`, `dept`, `warnings`, etc.) safely without deleting your existing data.

4. Open the app:
   ```
   http://localhost/UniFix/index.html
   ```

### Default Login Credentials

| Role | Username | Password |
|------|----------|----------|
| Admin | `admin1001` | `password123` |
| Solver | `solver2001` | `password123` |

> Register new Student and Teacher accounts from the registration pages.

---

## 🌍 Localization

UniFix supports full **English** and **Amharic** language switching across all pages with no page reload. All UI text is managed via a centralized `TRANSLATIONS` object in `main.js`.

---

## 📄 License

This project is licensed under the MIT License. See [LICENSE](LICENSE) for details.

---

## 👨‍💻 Authors

Built by the UniFix development team at **Debre Berhan University**.
