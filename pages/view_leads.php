<?php
$result = $conn->query("SELECT id, name, mobile, email, source, status, assigned_to, created_at FROM leads ORDER BY id DESC");
?>
<div class="card">
    <div class="card-header">
        <h2>All Leads</h2>
        <div class="card-header-actions">
            <div class="search-box">
                <span class="search-icon">&#8981;</span>
                <input type="text" class="table-search" data-table="tbl" placeholder="Search...">
            </div>
            <span class="badge badge-info record-count"><?= $result->num_rows ?></span>
            <a href="dashboard.php?page=add_lead" class="btn btn-primary btn-sm">+ Add</a>
        </div>
    </div>
    <?php if ($result->num_rows > 0): ?>
    <div class="table-wrapper">
        <table id="tbl">
            <thead><tr><th>ID</th><th>Name</th><th>Mobile</th><th>Email</th><th>Source</th><th>Status</th><th>Assigned</th><th>Created</th><th>Actions</th></tr></thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()):
                    $s = $row['status'];
                    $bc = 'badge-info';
                    if ($s === 'Converted') $bc = 'badge-success';
                    elseif ($s === 'Lost') $bc = 'badge-danger';
                    elseif ($s === 'Contacted' || $s === 'Qualified') $bc = 'badge-warning';
                    elseif ($s === 'New') $bc = 'badge-cyan';
                ?>
                <tr>
                    <td class="id-col"><?= $row['id'] ?></td>
                    <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                    <td><?= htmlspecialchars($row['mobile']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><span class="badge badge-purple"><?= htmlspecialchars($row['source'] ?: '-') ?></span></td>
                    <td><span class="badge <?= $bc ?>"><?= htmlspecialchars($s ?: '-') ?></span></td>
                    <td><?= htmlspecialchars($row['assigned_to'] ?: '-') ?></td>
                    <td style="font-size:11px; color:var(--text-4);"><?= htmlspecialchars($row['created_at'] ?: '-') ?></td>
                    <td><div class="actions-cell">
                        <a href="dashboard.php?page=edit_lead&id=<?= $row['id'] ?>" class="btn btn-outline btn-icon" title="Edit">&#9998;</a>
                        <button class="btn btn-danger btn-icon" onclick="confirmDelete('dashboard.php?delete=1&table=leads&id=<?= $row['id'] ?>')" title="Delete">&times;</button>
                    </div></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-icon">L</div>
        <h3>No leads</h3>
        <p>Add your first lead.</p>
    </div>
    <?php endif; ?>
</div>