<?php
$result = $conn->query("SELECT id, deal_id, invoice_no, amount, invoice_date, status FROM invoices ORDER BY id DESC");
?>
<div class="card">
    <div class="card-header">
        <h2>All Invoices</h2>
        <div class="card-header-actions">
            <div class="search-box">
                <span class="search-icon">&#8981;</span>
                <input type="text" class="table-search" data-table="tbl" placeholder="Search...">
            </div>
            <span class="badge badge-info record-count"><?= $result->num_rows ?></span>
            <a href="dashboard.php?page=add_invoice" class="btn btn-primary btn-sm">+ Add</a>
        </div>
    </div>
    <?php if ($result->num_rows > 0): ?>
    <div class="table-wrapper">
        <table id="tbl">
            <thead><tr><th>ID</th><th>Deal</th><th>Invoice No</th><th>Amount</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()):
                    $s = $row['status'];
                    $bc = 'badge-info';
                    if ($s === 'Paid') $bc = 'badge-success';
                    elseif ($s === 'Overdue' || $s === 'Cancelled') $bc = 'badge-danger';
                    elseif ($s === 'Unpaid') $bc = 'badge-warning';
                ?>
                <tr>
                    <td class="id-col"><?= $row['id'] ?></td>
                    <td>#<?= htmlspecialchars($row['deal_id']) ?></td>
                    <td><strong><?= htmlspecialchars($row['invoice_no']) ?></strong></td>
                    <td style="font-weight:600;">&#8377;<?= number_format((float)$row['amount']) ?></td>
                    <td><?= htmlspecialchars($row['invoice_date'] ?: '-') ?></td>
                    <td><span class="badge <?= $bc ?>"><?= htmlspecialchars($s ?: '-') ?></span></td>
                    <td><div class="actions-cell">
                        <a href="dashboard.php?page=edit_invoice&id=<?= $row['id'] ?>" class="btn btn-outline btn-icon" title="Edit">&#9998;</a>
                        <button class="btn btn-danger btn-icon" onclick="confirmDelete('dashboard.php?delete=1&table=invoices&id=<?= $row['id'] ?>')" title="Delete">&times;</button>
                    </div></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-icon">I</div>
        <h3>No invoices</h3>
        <p>Create your first invoice.</p>
    </div>
    <?php endif; ?>
</div>