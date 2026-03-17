<?php
$result = $conn->query("SELECT id, name, email, mobile FROM sales_users ORDER BY id DESC");
?>
<div class="card">
    <div class="card-header">
        <h2>All Sales Users</h2>
        <div class="card-header-actions">
            <div class="search-box">
                <span class="search-icon">&#8981;</span>
                <input type="text" class="table-search" data-table="tbl" placeholder="Search...">
            </div>
            <span class="badge badge-info record-count"><?= $result->num_rows ?></span>
            <a href="dashboard.php?page=add_sales_user" class="btn btn-primary btn-sm">+ Add</a>
        </div>
    </div>
    <?php if ($result->num_rows > 0): ?>
    <div class="table-wrapper">
        <table id="tbl">
            <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Mobile</th><th>Actions</th></tr></thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="id-col"><?= $row['id'] ?></td>
                    <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['mobile']) ?></td>
                    <td><div class="actions-cell">
                        <button class="btn btn-danger btn-icon" onclick="confirmDelete('dashboard.php?delete=1&table=sales_users&id=<?= $row['id'] ?>')" title="Delete">&times;</button>
                    </div></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-icon">U</div>
        <h3>No sales users</h3>
        <p>Add your first sales user.</p>
    </div>
    <?php endif; ?>
</div>