<?php
$result = $conn->query("SELECT id, username FROM admins ORDER BY id DESC");
?>
<div class="card">
    <div class="card-header">
        <h2>All Admins</h2>
        <div class="card-header-actions">
            <div class="search-box">
                <span class="search-icon">&#8981;</span>
                <input type="text" class="table-search" data-table="adminsTable" placeholder="Search...">
            </div>
            <span class="badge badge-info record-count"><?= $result->num_rows ?></span>
            <a href="dashboard.php?page=add_admin" class="btn btn-primary btn-sm">+ Add</a>
        </div>
    </div>
    <?php if ($result->num_rows > 0): ?>
    <div class="table-wrapper">
        <table id="adminsTable">
            <thead><tr><th>ID</th><th>Username</th><th>Actions</th></tr></thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="id-col"><?= $row['id'] ?></td>
                    <td><strong><?= htmlspecialchars($row['username']) ?></strong></td>
                    <td><div class="actions-cell">
                        <button class="btn btn-danger btn-icon" onclick="confirmDelete('dashboard.php?delete=1&table=admins&id=<?= $row['id'] ?>')" title="Delete">&times;</button>
                    </div></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-icon">A</div>
        <h3>No admins</h3>
        <p>Add an admin to get started.</p>
    </div>
    <?php endif; ?>
</div>