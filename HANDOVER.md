# Sales CRM Admin Panel — Handover Document

**Project:** Sales CRM Admin Panel  
**Date:** 17 March 2026  
**Author:** Aayush Bankar  
**Repository:** https://github.com/Aayushbankar/crm  
**License:** MIT  

---

## 1. Project Summary

A PHP/MySQL web application for managing the sales pipeline of a small-to-medium business. Admins can track leads, schedule follow-ups, manage deals, generate invoices, and set monthly sales targets — all through a clean dark-themed dashboard.

---

## 2. Deliverables

| # | Deliverable | Status |
|---|-------------|--------|
| 1 | Database schema (`db_setup.sql`) — 7 tables | Done |
| 2 | Admin authentication (login/logout) | Done |
| 3 | Dashboard with live stat cards + recent activity | Done |
| 4 | 7 Add pages (INSERT forms with validation) | Done |
| 5 | 7 View pages (SELECT tables with search + delete) | Done |
| 6 | 3 Edit pages (UPDATE forms for leads, deals, invoices) | Done |
| 7 | Delete functionality with confirmation modal | Done |
| 8 | Client-side search/filter on all tables | Done |
| 9 | Responsive design with mobile sidebar toggle | Done |
| 10 | `run.py` auto-launcher (prechecks + service start) | Done |
| 11 | README.md with diagrams, screenshots, setup guide | Done |
| 12 | MIT License | Done |
| 13 | Git repository pushed to GitHub | Done |

**Total files:** 25 (including LICENSE and screenshots directory)

---

## 3. Technology Stack

| Component | Version / Detail |
|-----------|-----------------|
| PHP | 8.x (via XAMPP) |
| MySQL | MariaDB 10.x (via XAMPP) |
| Web Server | Apache 2.4 (via XAMPP) |
| Frontend | HTML5, CSS3, Vanilla JavaScript |
| Font | Inter (Google Fonts CDN) |
| OS Tested | Windows 10/11 |

---

## 4. Prerequisites

| Requirement | Detail |
|-------------|--------|
| XAMPP | Installed at `C:\xampp` with Apache + MySQL |
| Python 3 | For `run.py` auto-launcher (optional) |
| Browser | Chrome, Firefox, Edge (modern) |
| Git | For version control (optional) |

---

## 5. Setup Instructions

### Quick Start
```bash
python run.py
```
This handles everything automatically: service startup, database creation, htdocs linking, and browser launch.

### Manual Setup
1. Start Apache and MySQL from XAMPP Control Panel
2. Run `C:\xampp\mysql\bin\mysql.exe -u root < db_setup.sql`
3. Create junction: `mklink /J "C:\xampp\htdocs\CRM" "d:\client\CRM"`
4. Open `http://localhost/CRM/login.php`
5. Login with `admin` / `admin123`

---

## 6. Database Details

**Database name:** `sales_crm`  
**Connection:** `config.php` connects via `mysqli` to `localhost:3306` as `root` (no password)

### Tables

| Table | Columns | Primary Operations |
|-------|---------|-------------------|
| `admins` | id, username, password | Login auth, CRUD |
| `sales_users` | id, name, email, mobile, password | CRUD |
| `leads` | id, name, mobile, email, source, status, assigned_to, created_at | CRUD + badges |
| `followups` | id, lead_id (FK→leads), followup_date, remarks, created_at | CRUD + JOIN |
| `deals` | id, lead_id (FK→leads), deal_value, expected_close, status | CRUD + JOIN |
| `invoices` | id, deal_id (FK→deals), invoice_no, amount, invoice_date, status | CRUD + JOIN |
| `targets` | id, sales_user_id (FK→sales_users), month, target_amount | CRUD + JOIN |

### Key Relationships
- `leads` → `followups` (one-to-many via `lead_id`)
- `leads` → `deals` (one-to-many via `lead_id`)
- `deals` → `invoices` (one-to-many via `deal_id`)
- `sales_users` → `targets` (one-to-many via `sales_user_id`)

---

## 7. File Inventory

```
CRM/
├── config.php           # DB connection + session
├── db_setup.sql         # Schema: CREATE DATABASE + 7 tables
├── login.php            # Auth page
├── logout.php           # Session destroy
├── dashboard.php        # Main layout + page router + delete handler
├── sidebar.php          # Navigation component
├── style.css            # Zinc dark theme (Inter font)
├── script.js            # Search, modal, sidebar toggle
├── run.py               # Auto-launcher
├── README.md            # Full documentation with diagrams
├── LICENSE              # MIT License
├── .gitignore           # Exclusions
├── srs.md               # Original requirements spec
├── screenshots/         # App screenshots (4 PNGs)
└── pages/               # 17 page modules
    ├── add_*.php (7)    # INSERT forms
    ├── view_*.php (7)   # SELECT tables
    └── edit_*.php (3)   # UPDATE forms
```

---

## 8. Security Notes

| Area | Current State | Recommendation for Production |
|------|--------------|-------------------------------|
| Passwords | Stored as plaintext | Use `password_hash()` / `password_verify()` |
| SQL Injection | Protected (prepared statements) | Already handled |
| XSS | Protected (`htmlspecialchars()` on output) | Already handled |
| CSRF | Not implemented | Add CSRF tokens to forms |
| Sessions | Basic PHP sessions | Add session regeneration + timeout |
| HTTPS | Not enforced | Enable SSL on Apache |
| DB Credentials | Root user, no password | Create dedicated MySQL user with limited privileges |

---

## 9. Known Limitations

1. **No role-based access** — All admins have identical permissions
2. **No pagination** — Tables load all records at once (fine for < 1000 rows)
3. **No file uploads** — No attachment support for leads/invoices
4. **No email integration** — Follow-up reminders are manual
5. **No API** — Frontend-only, no REST/JSON endpoints
6. **Single-server** — Designed for local XAMPP deployment

---

## 10. Future Enhancement Ideas

| Priority | Enhancement |
|----------|------------|
| High | Password hashing with `bcrypt` |
| High | CSRF token protection on forms |
| Medium | Pagination for large datasets |
| Medium | Export to CSV/PDF for reports |
| Medium | Dashboard charts (Chart.js) |
| Low | REST API for mobile app integration |
| Low | Role-based access control (RBAC) |
| Low | Email notifications for follow-ups |

---

## 11. Support & Maintenance

- **Logs:** PHP errors go to XAMPP's `C:\xampp\apache\logs\error.log`
- **DB Backup:** `C:\xampp\mysql\bin\mysqldump.exe -u root sales_crm > backup.sql`
- **DB Restore:** `C:\xampp\mysql\bin\mysql.exe -u root sales_crm < backup.sql`
- **Code Updates:** `git pull origin main` in `d:\client\CRM`

---

*This document serves as the official handover for the Sales CRM Admin Panel project.*
