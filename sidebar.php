<?php
$current_page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2>Sales CRM</h2>
        <span>Admin Panel</span>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">
            <a href="dashboard.php?page=home" class="nav-link <?= $current_page == 'home' ? 'active' : '' ?>">
                <span class="icon">⌂</span> Dashboard
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Admins</div>
            <a href="dashboard.php?page=add_admin" class="nav-link <?= $current_page == 'add_admin' ? 'active' : '' ?>">
                <span class="icon">+</span> Add Admin
            </a>
            <a href="dashboard.php?page=view_admins" class="nav-link <?= $current_page == 'view_admins' ? 'active' : '' ?>">
                <span class="icon">≡</span> View Admins
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Sales Users</div>
            <a href="dashboard.php?page=add_sales_user" class="nav-link <?= $current_page == 'add_sales_user' ? 'active' : '' ?>">
                <span class="icon">+</span> Add Sales User
            </a>
            <a href="dashboard.php?page=view_sales_users" class="nav-link <?= $current_page == 'view_sales_users' ? 'active' : '' ?>">
                <span class="icon">≡</span> View Sales Users
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Leads</div>
            <a href="dashboard.php?page=add_lead" class="nav-link <?= $current_page == 'add_lead' ? 'active' : '' ?>">
                <span class="icon">+</span> Add Lead
            </a>
            <a href="dashboard.php?page=view_leads" class="nav-link <?= $current_page == 'view_leads' ? 'active' : '' ?>">
                <span class="icon">≡</span> View Leads
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Followups</div>
            <a href="dashboard.php?page=add_followup" class="nav-link <?= $current_page == 'add_followup' ? 'active' : '' ?>">
                <span class="icon">+</span> Add Followup
            </a>
            <a href="dashboard.php?page=view_followups" class="nav-link <?= $current_page == 'view_followups' ? 'active' : '' ?>">
                <span class="icon">≡</span> View Followups
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Deals</div>
            <a href="dashboard.php?page=add_deal" class="nav-link <?= $current_page == 'add_deal' ? 'active' : '' ?>">
                <span class="icon">+</span> Add Deal
            </a>
            <a href="dashboard.php?page=view_deals" class="nav-link <?= $current_page == 'view_deals' ? 'active' : '' ?>">
                <span class="icon">≡</span> View Deals
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Invoices</div>
            <a href="dashboard.php?page=add_invoice" class="nav-link <?= $current_page == 'add_invoice' ? 'active' : '' ?>">
                <span class="icon">+</span> Add Invoice
            </a>
            <a href="dashboard.php?page=view_invoices" class="nav-link <?= $current_page == 'view_invoices' ? 'active' : '' ?>">
                <span class="icon">≡</span> View Invoices
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Targets</div>
            <a href="dashboard.php?page=add_target" class="nav-link <?= $current_page == 'add_target' ? 'active' : '' ?>">
                <span class="icon">+</span> Add Target
            </a>
            <a href="dashboard.php?page=view_targets" class="nav-link <?= $current_page == 'view_targets' ? 'active' : '' ?>">
                <span class="icon">≡</span> View Targets
            </a>
        </div>

        <div class="nav-section" style="margin-top: auto;">
            <a href="logout.php" class="nav-link logout-link">
                <span class="icon">←</span> Logout
            </a>
        </div>
    </nav>
    <div class="sidebar-footer">Sales CRM &copy; 2026</div>
</div>
