<?php
$success = '';
$error = '';
$deals_result = $conn->query("SELECT id, deal_value FROM deals ORDER BY id DESC");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deal_id = trim($_POST['deal_id'] ?? '');
    $invoice_no = trim($_POST['invoice_no'] ?? '');
    $amount = trim($_POST['amount'] ?? '');
    $invoice_date = trim($_POST['invoice_date'] ?? '');
    $status = trim($_POST['status'] ?? 'Unpaid');
    if (empty($deal_id) || empty($invoice_no) || empty($amount)) {
        $error = 'Deal, invoice number, and amount are required.';
    } else {
        $stmt = $conn->prepare("INSERT INTO invoices (deal_id, invoice_no, amount, invoice_date, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $deal_id, $invoice_no, $amount, $invoice_date, $status);
        if ($stmt->execute()) { $success = 'Invoice added!'; }
        else { $error = 'Error: ' . $conn->error; }
        $stmt->close();
    }
}
?>
<?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card">
    <div class="card-header">
        <h2>Add New Invoice</h2>
        <span class="badge badge-info">INSERT</span>
    </div>
    <form method="POST" data-validate>
        <div class="form-row">
            <div class="form-group">
                <label for="deal_id">Deal</label>
                <select id="deal_id" name="deal_id" class="form-control" required>
                    <option value="">Select a deal</option>
                    <?php if ($deals_result): while ($deal = $deals_result->fetch_assoc()): ?>
                        <option value="<?= $deal['id'] ?>">Deal #<?= $deal['id'] ?> - &#8377;<?= htmlspecialchars($deal['deal_value']) ?></option>
                    <?php endwhile; endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="invoice_no">Invoice Number</label>
                <input type="text" id="invoice_no" name="invoice_no" class="form-control" placeholder="e.g. INV-001" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="text" id="amount" name="amount" class="form-control" placeholder="Enter amount" required>
            </div>
            <div class="form-group">
                <label for="invoice_date">Invoice Date</label>
                <input type="date" id="invoice_date" name="invoice_date" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control">
                <option value="Unpaid">Unpaid</option>
                <option value="Paid">Paid</option>
                <option value="Overdue">Overdue</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">+ Add Invoice</button>
    </form>
</div>