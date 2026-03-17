<?php
$result = $conn->query("SELECT f.id, f.lead_id, l.name AS lead_name, f.followup_date, f.remarks, f.created_at FROM followups f LEFT JOIN leads l ON f.lead_id = l.id ORDER BY f.id DESC");
?>
<div class="card">
    <div class="card-header">
        <h2>All Followups</h2>
        <div class="card-header-actions">
            <div class="search-box">
                <span class="search-icon">&#8981;</span>
                <input type="text" class="table-search" data-table="tbl" placeholder="Search...">
            </div>
            <span class="badge badge-info record-count"><?= $result->num_rows ?></span>
            <a href="dashboard.php?page=add_followup" class="btn btn-primary btn-sm">+ Add</a>
        </div>
    </div>
    <?php if ($result->num_rows > 0): ?>
    <div class="table-wrapper">
        <table id="tbl">
            <thead><tr><th>ID</th><th>Lead</th><th>Date</th><th>Remarks</th><th>Created</th><th>Actions</th></tr></thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="id-col"><?= $row['id'] ?></td>
                    <td><strong><?= htmlspecialchars($row['lead_name'] ?: 'Lead #'.$row['lead_id']) ?></strong></td>
                    <td><span class="badge badge-cyan"><?= htmlspecialchars($row['followup_date']) ?></span></td>
                    <td style="max-width:250px;"><?= htmlspecialchars($row['remarks']) ?></td>
                    <td style="font-size:11px; color:var(--text-4);"><?= htmlspecialchars($row['created_at']) ?></td>
                    <td><div class="actions-cell">
                        <button class="btn btn-danger btn-icon" onclick="confirmDelete('dashboard.php?delete=1&table=followups&id=<?= $row['id'] ?>')" title="Delete">&times;</button>
                    </div></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-icon">F</div>
        <h3>No followups</h3>
        <p>Schedule your first followup.</p>
    </div>
    <?php endif; ?>
</div>