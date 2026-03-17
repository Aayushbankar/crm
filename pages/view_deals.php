<?php
$result = $conn->query("SELECT d.id, d.lead_id, l.name AS lead_name, d.deal_value, d.expected_close, d.status FROM deals d LEFT JOIN leads l ON d.lead_id = l.id ORDER BY d.id DESC");
?>
<div class="card">
    <div class="card-header">
        <h2>All Deals</h2>
        <div class="card-header-actions">
            <div class="search-box">
                <span class="search-icon">&#8981;</span>
                <input type="text" class="table-search" data-table="tbl" placeholder="Search...">
            </div>
            <span class="badge badge-info record-count"><?= $result->num_rows ?></span>
            <a href="dashboard.php?page=add_deal" class="btn btn-primary btn-sm">+ Add</a>
        </div>
    </div>
    <?php if ($result->num_rows > 0): ?>
    <div class="table-wrapper">
        <table id="tbl">
            <thead><tr><th>ID</th><th>Lead</th><th>Value</th><th>Expected Close</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()):
                    $s = $row['status'];
                    $bc = 'badge-info';
                    if ($s === 'Won') $bc = 'badge-success';
                    elseif ($s === 'Lost') $bc = 'badge-danger';
                    elseif ($s === 'Pending') $bc = 'badge-warning';
                ?>
                <tr>
                    <td class="id-col"><?= $row['id'] ?></td>
                    <td><strong><?= htmlspecialchars($row['lead_name'] ?: 'Lead #'.$row['lead_id']) ?></strong></td>
                    <td style="font-weight:600;">&#8377;<?= number_format((float)$row['deal_value']) ?></td>
                    <td><?= htmlspecialchars($row['expected_close'] ?: '-') ?></td>
                    <td><span class="badge <?= $bc ?>"><?= htmlspecialchars($s ?: '-') ?></span></td>
                    <td><div class="actions-cell">
                        <a href="dashboard.php?page=edit_deal&id=<?= $row['id'] ?>" class="btn btn-outline btn-icon" title="Edit">&#9998;</a>
                        <button class="btn btn-danger btn-icon" onclick="confirmDelete('dashboard.php?delete=1&table=deals&id=<?= $row['id'] ?>')" title="Delete">&times;</button>
                    </div></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-icon">D</div>
        <h3>No deals</h3>
        <p>Create your first deal.</p>
    </div>
    <?php endif; ?>
</div>