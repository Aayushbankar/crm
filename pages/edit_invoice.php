<?php
$success = '';
$error = '';
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { echo '<div class="alert alert-danger">Invalid invoice ID.</div>'; return; }
$deals_result = $conn->query("SELECT id, deal_value FROM deals ORDER BY id DESC");
$stmt = $conn->prepare("SELECT * FROM invoices WHERE id = ?");
$stmt->bind_param("i", $id); $stmt->execute();
$invoice = $stmt->get_result()->fetch_assoc(); $stmt->close();
if (!$invoice) { echo '<div class="alert alert-danger">Invoice not found.</div>'; return; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deal_id = trim($_POST['deal_id'] ?? ''); $invoice_no = trim($_POST['invoice_no'] ?? '');
    $amount = trim($_POST['amount'] ?? ''); $invoice_date = trim($_POST['invoice_date'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $stmt = $conn->prepare("UPDATE invoices SET deal_id=?, invoice_no=?, amount=?, invoice_date=?, status=? WHERE id=?");
    $stmt->bind_param("sssssi", $deal_id, $invoice_no, $amount, $invoice_date, $status, $id);
    if ($stmt->execute()) {
        $success = 'Invoice updated!';
        $st2 = $conn->prepare("SELECT * FROM invoices WHERE id = ?"); $st2->bind_param("i", $id); $st2->execute();
        $invoice = $st2->get_result()->fetch_assoc(); $st2->close();
    } else { $error = 'Error: ' . $conn->error; }
    $stmt->close();
}
?>
<?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card">
    <div class="card-header">
        <h2>Edit Invoice #<?= $id ?></h2>
        <a href="dashboard.php?page=view_invoices" class="btn btn-outline btn-sm">Back to Invoices</a>
    </div>
    <form method="POST" data-validate>
        <div class="form-row">
            <div class="form-group"><label for="deal_id">Deal</label>
                <select id="deal_id" name="deal_id" class="form-control" required>
                    <option value="">Select a deal</option>
                    <?php if ($deals_result): while ($deal = $deals_result->fetch_assoc()): ?>
                        <option value="<?= $deal['id'] ?>" <?= $invoice['deal_id'] == $deal['id'] ? 'selected' : '' ?>>Deal #<?= $deal['id'] ?> - &#8377;<?= htmlspecialchars($deal['deal_value']) ?></option>
                    <?php endwhile; endif; ?>
                </select>
            </div>
            <div class="form-group"><label for="invoice_no">Invoice Number</label><input type="text" id="invoice_no" name="invoice_no" class="form-control" value="<?= htmlspecialchars($invoice['invoice_no']) ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label for="amount">Amount</label><input type="text" id="amount" name="amount" class="form-control" value="<?= htmlspecialchars($invoice['amount']) ?>" required></div>
            <div class="form-group"><label for="invoice_date">Invoice Date</label><input type="date" id="invoice_date" name="invoice_date" class="form-control" value="<?= htmlspecialchars($invoice['invoice_date']) ?>"></div>
        </div>
        <div class="form-group"><label for="status">Status</label>
            <select id="status" name="status" class="form-control">
                <?php foreach (['Unpaid', 'Paid', 'Overdue', 'Cancelled'] as $opt): ?>
                    <option value="<?= $opt ?>" <?= $invoice['status'] === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Invoice</button>
    </form>
</div>