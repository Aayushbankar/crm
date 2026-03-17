<?php
$result = $conn->query("SELECT t.id, t.sales_user_id, s.name AS user_name, t.month, t.target_amount FROM targets t LEFT JOIN sales_users s ON t.sales_user_id = s.id ORDER BY t.id DESC");
?>
<div class="card">
    <div class="card-header">
        <h2>All Targets</h2>
        <div class="card-header-actions">
            <div class="search-box">
                <span class="search-icon">&#8981;</span>
                <input type="text" class="table-search" data-table="tbl" placeholder="Search...">
            </div>
            <span class="badge badge-info record-count"><?= $result->num_rows ?></span>
            <a href="dashboard.php?page=add_target" class="btn btn-primary btn-sm">+ Add</a>
        </div>
    </div>
    <?php if ($result->num_rows > 0): ?>
    <div class="table-wrapper">
        <table id="tbl">
            <thead><tr><th>ID</th><th>Sales User</th><th>Month</th><th>Target</th><th>Actions</th></tr></thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="id-col"><?= $row['id'] ?></td>
                    <td><strong><?= htmlspecialchars($row['user_name'] ?: 'User #'.$row['sales_user_id']) ?></strong></td>
                    <td><span class="badge badge-purple"><?= htmlspecialchars($row['month']) ?></span></td>
                    <td style="font-weight:600;">&#8377;<?= number_format((float)$row['target_amount']) ?></td>
                    <td><div class="actions-cell">
                        <button class="btn btn-danger btn-icon" onclick="confirmDelete('dashboard.php?delete=1&table=targets&id=<?= $row['id'] ?>')" title="Delete">&times;</button>
                    </div></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-icon">T</div>
        <h3>No targets</h3>
        <p>Set your first sales target.</p>
    </div>
    <?php endif; ?>
</div>