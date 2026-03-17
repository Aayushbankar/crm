# 🚀 Sales CRM Admin Panel

A **PHP/MySQL web-based Sales CRM Admin Panel** for managing sales users, leads, follow-ups, deals, invoices, and sales targets through a modern dark-themed dashboard interface.

---

## ✨ Features

| Feature | Description |
|---------|-------------|
| **Admin Authentication** | Secure login/logout with session management |
| **Dashboard Home** | Live stats from database with animated stat cards |
| **7 Add Modules** | Forms with validation to INSERT records (Admins, Sales Users, Leads, Followups, Deals, Invoices, Targets) |
| **7 View Modules** | Tables with live search/filter to display records (SELECT queries) |
| **Edit Records** | Edit forms for Leads, Deals, and Invoices with UPDATE queries |
| **Delete Records** | Delete any record with a confirmation modal |
| **Search & Filter** | Instant client-side search on all view tables |
| **Recent Activity** | Dashboard shows latest leads and their status |
| **Quick Actions** | One-click shortcuts to create leads, deals, invoices |
| **Responsive UI** | Mobile-friendly with sidebar toggle |
| **Premium Dark Theme** | Glassmorphism, gradients, micro-animations |

---

## 🛠️ Technology Stack

| Component | Technology |
|-----------|------------|
| Backend | PHP 8.x |
| Database | MySQL (MariaDB) |
| Frontend | HTML5, CSS3, JavaScript |
| Server | Apache (via XAMPP) |

---

## 📁 Project Structure

```
CRM/
├── config.php              # Database connection + session start
├── db_setup.sql            # SQL script to create database and 7 tables
├── login.php               # Admin login page
├── logout.php              # Session destroy + redirect
├── dashboard.php           # Main layout: sidebar + content router
├── sidebar.php             # Reusable sidebar navigation component
├── style.css               # Global styles (dark theme, components)
├── script.js               # Client-side: search, modals, animations
├── run.py                  # Auto-launcher: prechecks + start services
├── srs.md                  # Software Requirements Specification
├── README.md               # This file
└── pages/
    ├── add_admin.php        # INSERT → admins
    ├── add_sales_user.php   # INSERT → sales_users
    ├── add_lead.php         # INSERT → leads
    ├── add_followup.php     # INSERT → followups
    ├── add_deal.php         # INSERT → deals
    ├── add_invoice.php      # INSERT → invoices
    ├── add_target.php       # INSERT → targets
    ├── view_admins.php      # SELECT → admins
    ├── view_sales_users.php # SELECT → sales_users
    ├── view_leads.php       # SELECT → leads
    ├── view_followups.php   # SELECT → followups
    ├── view_deals.php       # SELECT → deals
    ├── view_invoices.php    # SELECT → invoices
    ├── view_targets.php     # SELECT → targets
    ├── edit_lead.php        # UPDATE → leads
    ├── edit_deal.php        # UPDATE → deals
    └── edit_invoice.php     # UPDATE → invoices
```

---

## 🗄️ Database Schema

Database name: **`sales_crm`**

All columns use `VARCHAR(255)` except `id` which is `INT AUTO_INCREMENT PRIMARY KEY`.

| Table | Columns |
|-------|---------|
| `admins` | id, username, password |
| `sales_users` | id, name, email, mobile, password |
| `leads` | id, name, mobile, email, source, status, assigned_to, created_at |
| `followups` | id, lead_id, followup_date, remarks, created_at |
| `deals` | id, lead_id, deal_value, expected_close, status |
| `invoices` | id, deal_id, invoice_no, amount, invoice_date, status |
| `targets` | id, sales_user_id, month, target_amount |

---

## ⚡ Quick Start

### Option 1: Automatic (Recommended)

```bash
python run.py
```

The launcher script will:
1. ✅ Verify XAMPP, PHP, and MySQL installation
2. ✅ Run PHP syntax checks on all files
3. ✅ Start MySQL and Apache services
4. ✅ Import the database schema (if not already done)
5. ✅ Create a junction link in `C:\xampp\htdocs\CRM`
6. ✅ Open the app in your browser

### Option 2: Manual Setup

**Prerequisites:** XAMPP installed at `C:\xampp`

**Step 1 — Start Services**
- Open XAMPP Control Panel
- Start **Apache** and **MySQL**

**Step 2 — Create Database**
```bash
C:\xampp\mysql\bin\mysql.exe -u root < d:\client\CRM\db_setup.sql
```

**Step 3 — Link Project to htdocs**
```bash
mklink /J "C:\xampp\htdocs\CRM" "d:\client\CRM"
```

**Step 4 — Open Browser**
```
http://localhost/CRM/login.php
```

---

## 🔐 Default Login

| Field | Value |
|-------|-------|
| Username | `admin` |
| Password | `admin123` |

---

## 📖 How It Works

### Architecture

The system follows a **3-layer architecture**:

```
┌──────────────────────────┐
│   Presentation Layer     │  HTML + CSS + JavaScript
│   (Frontend)             │  Forms, Tables, Sidebar
├──────────────────────────┤
│   Application Layer      │  PHP
│   (Backend Logic)        │  Form handling, SQL queries, sessions
├──────────────────────────┤
│   Data Layer             │  MySQL
│   (Database)             │  7 tables in sales_crm database
└──────────────────────────┘
```

### Page Routing

All pages are served through `dashboard.php` using a `?page=` query parameter:

```
dashboard.php?page=home          → Dashboard with stats
dashboard.php?page=add_lead      → Add Lead form
dashboard.php?page=view_leads    → View Leads table
dashboard.php?page=edit_lead&id=5 → Edit Lead #5
```

The sidebar (`sidebar.php`) is included on every page and highlights the active link.

### Add Pages (INSERT)

Each "Add" page contains:
- An HTML form with input fields
- PHP backend that validates input and runs `INSERT INTO` via prepared statements
- Success/error alerts with auto-dismissal

### View Pages (SELECT)

Each "View" page contains:
- A `SELECT` query fetching all records (with JOINs where relevant)
- A search bar for instant client-side filtering
- An HTML table with styled rows and status badges
- Edit and Delete action buttons per row

### Edit Pages (UPDATE)

Edit pages for Leads, Deals, and Invoices:
- Fetch existing record by ID
- Pre-fill the form with current values
- Run `UPDATE` query on submission via prepared statements

### Delete Operations

- Delete is handled globally in `dashboard.php` via GET parameters
- A confirmation modal prevents accidental deletion
- After deletion, user is redirected back to the view page

### Authentication

- `login.php` checks credentials against the `admins` table
- Session stores `admin_id` and `admin_username`
- `dashboard.php` redirects to login if session is missing
- `logout.php` destroys the session

---

## 🎨 UI Features

- **Dark Theme** — Deep navy/indigo color palette with glassmorphism
- **Stat Cards** — Gradient-topped cards with hover animations and live counts
- **Search Bars** — Instant filtering on all view tables
- **Status Badges** — Color-coded badges (Success, Danger, Warning, Info, Purple, Cyan)
- **Delete Modals** — Blur overlay confirmation before deletion
- **Toast Notifications** — Slide-in toasts for validation feedback
- **Staggered Animations** — Cards animate in sequence on page load
- **Responsive Design** — Mobile sidebar toggle, adaptive grid layouts

---

## 📝 License

This project is for internal/educational use.
