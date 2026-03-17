"""
Sales CRM Admin Panel — Launcher & Precheck Script
Run this script to verify your environment, start services, and launch the app.
Usage: python run.py
"""

import subprocess
import os
import sys
import time
import socket
import shutil

# ===== Configuration =====
XAMPP_PATH = r"C:\xampp"
PHP_PATH = os.path.join(XAMPP_PATH, "php", "php.exe")
MYSQL_PATH = os.path.join(XAMPP_PATH, "mysql", "bin", "mysql.exe")
MYSQLD_PATH = os.path.join(XAMPP_PATH, "mysql", "bin", "mysqld.exe")
APACHE_PATH = os.path.join(XAMPP_PATH, "apache", "bin", "httpd.exe")
XAMPP_CONTROL = os.path.join(XAMPP_PATH, "xampp-control.exe")
HTDOCS_PATH = os.path.join(XAMPP_PATH, "htdocs")
PROJECT_DIR = os.path.dirname(os.path.abspath(__file__))
SYMLINK_PATH = os.path.join(HTDOCS_PATH, "CRM")
DB_SETUP_FILE = os.path.join(PROJECT_DIR, "db_setup.sql")
APP_URL = "http://localhost/CRM/login.php"

# ===== Colors =====
class C:
    GREEN = "\033[92m"
    RED = "\033[91m"
    YELLOW = "\033[93m"
    CYAN = "\033[96m"
    BOLD = "\033[1m"
    END = "\033[0m"

def ok(msg):
    print(f"  {C.GREEN}✓{C.END} {msg}")

def fail(msg):
    print(f"  {C.RED}✗{C.END} {msg}")

def warn(msg):
    print(f"  {C.YELLOW}!{C.END} {msg}")

def info(msg):
    print(f"  {C.CYAN}→{C.END} {msg}")

def header(msg):
    print(f"\n{C.BOLD}{C.CYAN}{'='*50}{C.END}")
    print(f"{C.BOLD}  {msg}{C.END}")
    print(f"{C.CYAN}{'='*50}{C.END}\n")

def is_port_open(port):
    """Check if a port is in use (service is running)."""
    with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
        s.settimeout(1)
        return s.connect_ex(("127.0.0.1", port)) == 0

# ===== Prechecks =====
def check_xampp():
    header("1. PRECHECK — XAMPP Installation")
    
    if not os.path.isdir(XAMPP_PATH):
        fail(f"XAMPP not found at {XAMPP_PATH}")
        print(f"\n  Please install XAMPP from https://www.apachefriends.org/")
        return False
    ok(f"XAMPP found at {XAMPP_PATH}")

    checks = [
        ("PHP", PHP_PATH),
        ("MySQL", MYSQL_PATH),
        ("Apache", APACHE_PATH),
    ]
    all_ok = True
    for name, path in checks:
        if os.path.isfile(path):
            ok(f"{name} binary found")
        else:
            fail(f"{name} binary NOT found at {path}")
            all_ok = False

    return all_ok

def check_php_files():
    header("2. PRECHECK — PHP Syntax Validation")
    
    php_files = []
    for root, dirs, files in os.walk(PROJECT_DIR):
        for f in files:
            if f.endswith(".php"):
                php_files.append(os.path.join(root, f))

    if not php_files:
        fail("No PHP files found in project directory!")
        return False

    all_ok = True
    for filepath in php_files:
        result = subprocess.run(
            [PHP_PATH, "-l", filepath],
            capture_output=True, text=True
        )
        basename = os.path.relpath(filepath, PROJECT_DIR)
        if result.returncode == 0:
            ok(f"{basename}")
        else:
            fail(f"{basename} — {result.stdout.strip()}")
            all_ok = False

    if all_ok:
        info(f"All {len(php_files)} PHP files passed syntax check")
    return all_ok

# ===== Service Management =====
def start_services():
    header("3. STARTING SERVICES")

    # --- MySQL ---
    if is_port_open(3306):
        ok("MySQL is already running (port 3306)")
    else:
        info("Starting MySQL...")
        subprocess.Popen(
            [MYSQLD_PATH, "--console"],
            stdout=subprocess.DEVNULL,
            stderr=subprocess.DEVNULL,
            creationflags=subprocess.CREATE_NEW_PROCESS_GROUP
        )
        # Wait for MySQL to start
        for i in range(15):
            time.sleep(1)
            if is_port_open(3306):
                ok("MySQL started successfully")
                break
        else:
            fail("MySQL failed to start within 15 seconds")
            warn("Try starting MySQL manually via XAMPP Control Panel")
            return False

    # --- Apache ---
    if is_port_open(80):
        ok("Apache is already running (port 80)")
    else:
        info("Starting Apache...")
        subprocess.Popen(
            [APACHE_PATH, "-k", "start"],
            stdout=subprocess.DEVNULL,
            stderr=subprocess.DEVNULL,
            creationflags=subprocess.CREATE_NEW_PROCESS_GROUP
        )
        for i in range(10):
            time.sleep(1)
            if is_port_open(80):
                ok("Apache started successfully")
                break
        else:
            fail("Apache failed to start within 10 seconds")
            warn("Port 80 may be in use. Try starting Apache manually via XAMPP Control Panel")
            return False

    return True

# ===== Database Setup =====
def setup_database():
    header("4. DATABASE SETUP")

    if not os.path.isfile(DB_SETUP_FILE):
        fail(f"db_setup.sql not found at {DB_SETUP_FILE}")
        return False

    info("Importing database schema...")
    result = subprocess.run(
        [MYSQL_PATH, "-u", "root", "-e", "USE sales_crm; SHOW TABLES;"],
        capture_output=True, text=True
    )

    if result.returncode == 0 and "admins" in result.stdout:
        ok("Database 'sales_crm' already exists with tables")
        # Show tables
        tables = [line.strip() for line in result.stdout.strip().split("\n") if line.strip() and line.strip() != "Tables_in_sales_crm"]
        info(f"Tables found: {', '.join(tables)}")
    else:
        info("Creating database and tables...")
        with open(DB_SETUP_FILE, "r") as f:
            sql = f.read()
        result = subprocess.run(
            [MYSQL_PATH, "-u", "root"],
            input=sql, capture_output=True, text=True
        )
        if result.returncode == 0:
            ok("Database setup completed successfully")
        else:
            fail(f"Database setup failed: {result.stderr.strip()}")
            return False

    return True

# ===== Project Symlink =====
def setup_symlink():
    header("5. PROJECT ACCESS SETUP")

    if os.path.exists(SYMLINK_PATH):
        # Check if it's already pointing to our project
        if os.path.islink(SYMLINK_PATH) or os.path.isdir(SYMLINK_PATH):
            ok(f"CRM folder already exists in htdocs")
            return True

    info(f"Creating symlink: {SYMLINK_PATH} → {PROJECT_DIR}")
    try:
        os.symlink(PROJECT_DIR, SYMLINK_PATH, target_is_directory=True)
        ok("Symlink created successfully")
    except OSError as e:
        if "privilege" in str(e).lower() or "1314" in str(e):
            warn("Symlink creation requires admin privileges")
            info("Trying directory junction instead...")
            try:
                result = subprocess.run(
                    ["cmd", "/c", "mklink", "/J", SYMLINK_PATH, PROJECT_DIR],
                    capture_output=True, text=True
                )
                if result.returncode == 0:
                    ok("Directory junction created successfully")
                else:
                    fail("Could not create junction")
                    warn(f"Manually copy or link project to: {SYMLINK_PATH}")
                    return False
            except Exception as e2:
                fail(f"Junction creation failed: {e2}")
                return False
        else:
            fail(f"Symlink failed: {e}")
            return False

    return True

# ===== Launch =====
def launch_app():
    header("6. LAUNCHING APPLICATION")

    info(f"Opening browser at: {C.BOLD}{APP_URL}{C.END}")
    print()
    print(f"  {C.BOLD}{C.GREEN}╔═══════════════════════════════════════════╗{C.END}")
    print(f"  {C.BOLD}{C.GREEN}║  Sales CRM is ready!                      ║{C.END}")
    print(f"  {C.BOLD}{C.GREEN}║                                           ║{C.END}")
    print(f"  {C.BOLD}{C.GREEN}║  URL:  {APP_URL}    ║{C.END}")
    print(f"  {C.BOLD}{C.GREEN}║  User: admin                              ║{C.END}")
    print(f"  {C.BOLD}{C.GREEN}║  Pass: admin123                           ║{C.END}")
    print(f"  {C.BOLD}{C.GREEN}╚═══════════════════════════════════════════╝{C.END}")
    print()

    try:
        os.startfile(APP_URL)
        ok("Browser opened")
    except Exception:
        warn("Could not open browser automatically")
        info(f"Please open manually: {APP_URL}")

# ===== Main =====
def main():
    print(f"\n{C.BOLD}{C.CYAN}")
    print("  ╔═══════════════════════════════════════╗")
    print("  ║     Sales CRM — Setup & Launcher      ║")
    print("  ╚═══════════════════════════════════════╝")
    print(f"{C.END}")

    # Step 1: Prechecks
    if not check_xampp():
        print(f"\n{C.RED}Setup aborted. Fix the issues above and try again.{C.END}")
        sys.exit(1)

    if not check_php_files():
        print(f"\n{C.RED}PHP syntax errors found. Fix them and try again.{C.END}")
        sys.exit(1)

    # Step 2: Start services
    if not start_services():
        print(f"\n{C.YELLOW}Could not start services automatically.{C.END}")
        info("Please start Apache and MySQL via XAMPP Control Panel and re-run this script.")
        sys.exit(1)

    # Step 3: Database
    if not setup_database():
        print(f"\n{C.RED}Database setup failed. Check MySQL connection.{C.END}")
        sys.exit(1)

    # Step 4: Symlink
    if not setup_symlink():
        warn("Symlink setup failed — you may need to configure access manually")

    # Step 5: Launch
    launch_app()

    print(f"\n{C.BOLD}Press Ctrl+C to stop the services when done.{C.END}\n")

    # Keep script running so user can Ctrl+C
    try:
        while True:
            time.sleep(1)
    except KeyboardInterrupt:
        print(f"\n{C.YELLOW}Shutting down...{C.END}")
        sys.exit(0)

if __name__ == "__main__":
    main()
