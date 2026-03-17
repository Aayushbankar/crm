# Software Requirements Specification (SRS)

**Project Title:** Sales CRM Admin Panel
**Technology Stack:** PHP, MySQL, HTML, CSS, JavaScript
**System Type:** Web-based Admin Management System

---

# 1. Introduction

## 1.1 Purpose

The purpose of this system is to develop a **web-based Sales CRM Admin Panel** that allows administrators to manage sales users, leads, follow-ups, deals, invoices, and sales targets.

The system will allow administrators to **add data and view data** using a dashboard interface.

## 1.2 Scope

The system will provide functionality for:

* Admin management
* Sales user management
* Lead management
* Follow-up management
* Deal management
* Invoice management
* Sales target management

All records will be stored in **a single MySQL database** and managed through **PHP backend scripts** with a **frontend interface built using HTML, CSS, and JavaScript**.

---

# 2. Technology Stack

| Component               | Technology |
| ----------------------- | ---------- |
| Backend                 | PHP        |
| Database                | MySQL      |
| Frontend                | HTML       |
| Styling                 | CSS        |
| Client-side interaction | JavaScript |

---

# 3. System Constraints

1. All tables must be created in **one MySQL database**.
2. The `id` column must be **Primary Key and Auto Increment**.
3. All columns except `id` must use **VARCHAR datatype**.
4. Column names must **not contain spaces**.
5. Column names must **not contain special characters or symbols**.
6. Only **letters, numbers, and underscores** are allowed in column names.

Example of valid column names:

```
created_at
lead_id
target_amount
```

Invalid column names:

```
created at
lead-id
target@amount
```

---

# 4. Database Design

## 4.1 Admins Table

Stores administrator login credentials.

| Column   | Type                              |
| -------- | --------------------------------- |
| id       | INT (Primary Key, Auto Increment) |
| username | VARCHAR                           |
| password | VARCHAR                           |

SQL:

```sql
CREATE TABLE admins (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(255),
password VARCHAR(255)
);
```

---

## 4.2 Sales Users Table

Stores details of sales employees.

| Column   | Type    |
| -------- | ------- |
| id       | INT     |
| name     | VARCHAR |
| email    | VARCHAR |
| mobile   | VARCHAR |
| password | VARCHAR |

SQL:

```sql
CREATE TABLE sales_users (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255),
email VARCHAR(255),
mobile VARCHAR(255),
password VARCHAR(255)
);
```

---

## 4.3 Leads Table

Stores customer lead information.

| Column      | Type    |
| ----------- | ------- |
| id          | INT     |
| name        | VARCHAR |
| mobile      | VARCHAR |
| email       | VARCHAR |
| source      | VARCHAR |
| status      | VARCHAR |
| assigned_to | VARCHAR |
| created_at  | VARCHAR |

SQL:

```sql
CREATE TABLE leads (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255),
mobile VARCHAR(255),
email VARCHAR(255),
source VARCHAR(255),
status VARCHAR(255),
assigned_to VARCHAR(255),
created_at VARCHAR(255)
);
```

---

## 4.4 Followups Table

Stores follow-up records related to leads.

| Column        | Type    |
| ------------- | ------- |
| id            | INT     |
| lead_id       | VARCHAR |
| followup_date | VARCHAR |
| remarks       | VARCHAR |
| created_at    | VARCHAR |

SQL:

```sql
CREATE TABLE followups (
id INT AUTO_INCREMENT PRIMARY KEY,
lead_id VARCHAR(255),
followup_date VARCHAR(255),
remarks VARCHAR(255),
created_at VARCHAR(255)
);
```

---

## 4.5 Deals Table

Stores information about deals converted from leads.

| Column         | Type    |
| -------------- | ------- |
| id             | INT     |
| lead_id        | VARCHAR |
| deal_value     | VARCHAR |
| expected_close | VARCHAR |
| status         | VARCHAR |

SQL:

```sql
CREATE TABLE deals (
id INT AUTO_INCREMENT PRIMARY KEY,
lead_id VARCHAR(255),
deal_value VARCHAR(255),
expected_close VARCHAR(255),
status VARCHAR(255)
);
```

---

## 4.6 Invoices Table

Stores invoice records related to deals.

| Column       | Type    |
| ------------ | ------- |
| id           | INT     |
| deal_id      | VARCHAR |
| invoice_no   | VARCHAR |
| amount       | VARCHAR |
| invoice_date | VARCHAR |
| status       | VARCHAR |

SQL:

```sql
CREATE TABLE invoices (
id INT AUTO_INCREMENT PRIMARY KEY,
deal_id VARCHAR(255),
invoice_no VARCHAR(255),
amount VARCHAR(255),
invoice_date VARCHAR(255),
status VARCHAR(255)
);
```

---

## 4.7 Targets Table

Stores monthly sales targets for sales users.

| Column        | Type    |
| ------------- | ------- |
| id            | INT     |
| sales_user_id | VARCHAR |
| month         | VARCHAR |
| target_amount | VARCHAR |

SQL:

```sql
CREATE TABLE targets (
id INT AUTO_INCREMENT PRIMARY KEY,
sales_user_id VARCHAR(255),
month VARCHAR(255),
target_amount VARCHAR(255)
);
```

---

# 5. System Architecture

The system will follow a **3-layer architecture**.

### 1. Presentation Layer

User interface built with:

* HTML
* CSS
* JavaScript

Displays forms and tables for data entry and viewing.

### 2. Application Layer

Backend logic written in **PHP**.

Responsibilities:

* Handle form submission
* Execute SQL queries
* Process user input
* Communicate with database

### 3. Data Layer

Database management using **MySQL**.

Stores all records related to admins, users, leads, deals, and invoices.

---

# 6. Admin Dashboard

After login, the admin panel will display a **left sidebar navigation menu**.

Menu options:

* Home
* Add Admins
* View Admins
* Add Sales Users
* View Sales Users
* Add Leads
* View Leads
* Add Followups
* View Followups
* Add Deals
* View Deals
* Add Invoices
* View Invoices
* Add Targets
* View Targets
* Logout

The sidebar must appear on the **left side of the dashboard interface**.

---

# 7. Functional Requirements

## 7.1 Add Operations

All pages containing **Add** must perform **INSERT queries**.

Modules:

* Add Admins
* Add Sales Users
* Add Leads
* Add Followups
* Add Deals
* Add Invoices
* Add Targets

Each module must contain:

* Input form
* Submit button
* PHP script executing INSERT query.

---

## 7.2 View Operations

All pages containing **View** must perform **SELECT queries**.

Modules:

* View Admins
* View Sales Users
* View Leads
* View Followups
* View Deals
* View Invoices
* View Targets

Each module must:

* Retrieve records from MySQL
* Display records in **HTML table format**.

---

# 8. Image Handling

The system may support:

* Image upload using HTML form
* Storage of image path in database
* Display images on view pages.

---

# 9. Excluded Pages

The following pages are **not required for development currently**:

* Home Page
* Logout Page

All other modules must be implemented.

---

# 10. Summary

The project will implement a **PHP-based CRM Admin Panel** connected to a **MySQL database** with a **frontend interface** built using **HTML, CSS, and JavaScript**.

The system will allow administrators to **insert and view records** related to sales management using structured database tables within a single database.
