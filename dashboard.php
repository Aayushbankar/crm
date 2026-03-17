<?php
require_once 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$admin_name = $_SESSION['admin_username'] ?? 'Admin';

// Handle delete
if (isset($_GET['delete']) && isset($_GET['table']) && isset($_GET['id'])) {
    $allowed = ['admins', 'sales_users', 'leads', 'followups', 'deals', 'invoices', 'targets'];
    $table = $_GET['table'];
    $id = intval($_GET['id']);
    if (in_array($table, $allowed) && $id > 0) {
        $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $rp = 'view_' . $table;
        header("Location: dashboard.php?page=$rp&deleted=1");
        exit;
    }
}

$titles = [
    'home' => 'Dashboard', 'add_admin' => 'Add Admin', 'view_admins' => 'View Admins',
    'add_sales_user' => 'Add Sales User', 'view_sales_users' => 'View Sales Users',
    'add_lead' => 'Add Lead', 'view_leads' => 'View Leads',
    'add_followup' => 'Add Followup', 'view_followups' => 'View Followups',
    'add_deal' => 'Add Deal', 'view_deals' => 'View Deals',
    'add_invoice' => 'Add Invoice', 'view_invoices' => 'View Invoices',
    'add_target' => 'Add Target', 'view_targets' => 'View Targets',
    'edit_lead' => 'Edit Lead', 'edit_deal' => 'Edit Deal', 'edit_invoice' => 'Edit Invoice',
];
$title = $titles[$page] ?? 'Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> — Sales CRM</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <button class="sidebar-toggle" id="sidebarToggle">&#9776;</button>

    <div class="dashboard">
        <?php include 'sidebar.php'; ?>

        <div class="main-content">
            <div class="content-header">
                <h1><?= htmlspecialchars($title) ?></h1>
                <span class="breadcrumb">
                    <span class="admin-badge"><?= htmlspecialchars($admin_name) ?></span>
                </span>
            </div>

            <div class="content-body">
                <?php if (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success">Record deleted.</div>
                <?php endif; ?>

                <?php
                $pf = 'pages/' . $page . '.php';
                if (file_exists($pf)) {
                    include $pf;
                } else {
                    // === HOME ===
                    $stats = [];
                    $tbls = ['admins', 'sales_users', 'leads', 'followups', 'deals', 'invoices', 'targets'];
                    foreach ($tbls as $t) {
                        $r = $conn->query("SELECT COUNT(*) as c FROM $t");
                        $stats[$t] = $r ? $r->fetch_assoc()['c'] : 0;
                    }
                    $recent = $conn->query("SELECT name, status, created_at FROM leads ORDER BY id DESC LIMIT 5");
                    ?>

                    <div class="stats-grid">
                        <?php
                        $cards = [
                            ['admins', 'Admins', 'purple', 'A', 'view_admins'],
                            ['sales_users', 'Sales Users', 'cyan', 'U', 'view_sales_users'],
                            ['leads', 'Leads', 'pink', 'L', 'view_leads'],
                            ['followups', 'Followups', 'orange', 'F', 'view_followups'],
                            ['deals', 'Deals', 'green', 'D', 'view_deals'],
                            ['invoices', 'Invoices', 'blue', 'I', 'view_invoices'],
                            ['targets', 'Targets', 'red', 'T', 'view_targets'],
                        ];
                        foreach ($cards as $c):
                        ?>
                        <div class="stat-card <?= $c[2] ?>">
                            <div class="stat-icon"><?= $c[3] ?></div>
                            <div class="stat-value"><?= $stats[$c[0]] ?></div>
                            <div class="stat-label"><?= $c[1] ?></div>
                            <a href="dashboard.php?page=<?= $c[4] ?>" class="stat-link">View all &rarr;</a>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="grid-3">
                        <div class="card">
                            <div class="card-header">
                                <h2>Recent Leads</h2>
                                <a href="dashboard.php?page=view_leads" class="btn btn-outline btn-sm">View all</a>
                            </div>
                            <?php if ($recent && $recent->num_rows > 0): ?>
                            <ul class="activity-list">
                                <?php while ($row = $recent->fetch_assoc()): ?>
                                <li class="activity-item">
                                    <span class="activity-dot <?= $row['status'] === 'Converted' ? 'green' : ($row['status'] === 'New' ? 'cyan' : 'purple') ?>"></span>
                                    <div>
                                        <div class="activity-text"><strong><?= htmlspecialchars($row['name']) ?></strong> &mdash; <?= htmlspecialchars($row['status'] ?: 'New') ?></div>
                                        <div class="activity-time"><?= htmlspecialchars($row['created_at'] ?: '—') ?></div>
                                    </div>
                                </li>
                                <?php endwhile; ?>
                            </ul>
                            <?php else: ?>
                            <p style="color:var(--text-3); font-size:13px;">No leads yet. <a href="dashboard.php?page=add_lead" style="color:var(--blue);">Add one</a>.</p>
                            <?php endif; ?>
                        </div>

                        <div class="card">
                            <div class="card-header"><h2>Quick Actions</h2></div>
                            <div style="display:flex; flex-direction:column; gap:8px;">
                                <a href="dashboard.php?page=add_lead" class="btn btn-primary" style="justify-content:center;">+ New Lead</a>
                                <a href="dashboard.php?page=add_deal" class="btn btn-success" style="justify-content:center;">+ New Deal</a>
                                <a href="dashboard.php?page=add_invoice" class="btn btn-outline" style="justify-content:center;">+ New Invoice</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box">
            <div class="modal-icon">&#x26A0;</div>
            <h3>Delete this record?</h3>
            <p>This cannot be undone.</p>
            <div class="modal-actions">
                <button class="btn btn-outline" onclick="closeDeleteModal()">Cancel</button>
                <a href="#" class="btn btn-danger" id="confirmDeleteBtn" style="border-color:var(--rose); background:var(--rose); color:#fff;">Delete</a>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
