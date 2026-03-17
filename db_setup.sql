-- Sales CRM Database Setup
-- Run: C:\xampp\mysql\bin\mysql.exe -u root < d:\client\CRM\db_setup.sql

CREATE DATABASE IF NOT EXISTS sales_crm;
USE sales_crm;

-- 4.1 Admins Table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    password VARCHAR(255)
);

-- 4.2 Sales Users Table
CREATE TABLE IF NOT EXISTS sales_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255),
    mobile VARCHAR(255),
    password VARCHAR(255)
);

-- 4.3 Leads Table
CREATE TABLE IF NOT EXISTS leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    mobile VARCHAR(255),
    email VARCHAR(255),
    source VARCHAR(255),
    status VARCHAR(255),
    assigned_to VARCHAR(255),
    created_at VARCHAR(255)
);

-- 4.4 Followups Table
CREATE TABLE IF NOT EXISTS followups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lead_id VARCHAR(255),
    followup_date VARCHAR(255),
    remarks VARCHAR(255),
    created_at VARCHAR(255)
);

-- 4.5 Deals Table
CREATE TABLE IF NOT EXISTS deals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lead_id VARCHAR(255),
    deal_value VARCHAR(255),
    expected_close VARCHAR(255),
    status VARCHAR(255)
);

-- 4.6 Invoices Table
CREATE TABLE IF NOT EXISTS invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    deal_id VARCHAR(255),
    invoice_no VARCHAR(255),
    amount VARCHAR(255),
    invoice_date VARCHAR(255),
    status VARCHAR(255)
);

-- 4.7 Targets Table
CREATE TABLE IF NOT EXISTS targets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sales_user_id VARCHAR(255),
    month VARCHAR(255),
    target_amount VARCHAR(255)
);

-- Default admin account
INSERT INTO admins (username, password) VALUES ('admin', 'admin123');
