<?php
$success = '';
$error = '';
$leads_result = $conn->query("SELECT id, name FROM leads ORDER BY name");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lead_id = trim($_POST['lead_id'] ?? '');
    $deal_value = trim($_POST['deal_value'] ?? '');
    $expected_close = trim($_POST['expected_close'] ?? '');
    $status = trim($_POST['status'] ?? 'Open');
    if (empty($lead_id) || empty($deal_value)) {
        $error = 'Lead and deal value are required.';
    } else {
        $stmt = $conn->prepare("INSERT INTO deals (lead_id, deal_value, expected_close, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $lead_id, $deal_value, $expected_close, $status);
        if ($stmt->execute()) { $success = 'Deal added!'; }
        else { $error = 'Error: ' . $conn->error; }
        $stmt->close();
    }
}
?>
<?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card">
    <div class="card-header">
        <h2>Add New Deal</h2>
        <span class="badge badge-info">INSERT</span>
    </div>
    <form method="POST" data-validate>
        <div class="form-row">
            <div class="form-group">
                <label for="lead_id">Lead</label>
                <select id="lead_id" name="lead_id" class="form-control" required>
                    <option value="">Select a lead</option>
                    <?php if ($leads_result): while ($lead = $leads_result->fetch_assoc()): ?>
                        <option value="<?= $lead['id'] ?>"><?= htmlspecialchars($lead['id'] . ' - ' . $lead['name']) ?></option>
                    <?php endwhile; endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="deal_value">Deal Value</label>
                <input type="text" id="deal_value" name="deal_value" class="form-control" placeholder="Enter deal value" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="expected_close">Expected Close Date</label>
                <input type="date" id="expected_close" name="expected_close" class="form-control">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="Open">Open</option>
                    <option value="Won">Won</option>
                    <option value="Lost">Lost</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">+ Add Deal</button>
    </form>
</div>