<?php
$success = '';
$error = '';
$leads_result = $conn->query("SELECT id, name FROM leads ORDER BY name");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lead_id = trim($_POST['lead_id'] ?? '');
    $followup_date = trim($_POST['followup_date'] ?? '');
    $remarks = trim($_POST['remarks'] ?? '');
    $created_at = date('Y-m-d H:i:s');
    if (empty($lead_id) || empty($followup_date)) {
        $error = 'Lead and date are required.';
    } else {
        $stmt = $conn->prepare("INSERT INTO followups (lead_id, followup_date, remarks, created_at) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $lead_id, $followup_date, $remarks, $created_at);
        if ($stmt->execute()) { $success = 'Followup added!'; }
        else { $error = 'Error: ' . $conn->error; }
        $stmt->close();
    }
}
?>
<?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card">
    <div class="card-header">
        <h2>Add New Followup</h2>
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
                <label for="followup_date">Followup Date</label>
                <input type="date" id="followup_date" name="followup_date" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label for="remarks">Remarks</label>
            <textarea id="remarks" name="remarks" class="form-control" placeholder="Enter remarks"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">+ Add Followup</button>
    </form>
</div>