<?php
$success = '';
$error = '';
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { echo '<div class="alert alert-danger">Invalid deal ID.</div>'; return; }
$leads_result = $conn->query("SELECT id, name FROM leads ORDER BY name");
$stmt = $conn->prepare("SELECT * FROM deals WHERE id = ?");
$stmt->bind_param("i", $id); $stmt->execute();
$deal = $stmt->get_result()->fetch_assoc(); $stmt->close();
if (!$deal) { echo '<div class="alert alert-danger">Deal not found.</div>'; return; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lead_id = trim($_POST['lead_id'] ?? ''); $deal_value = trim($_POST['deal_value'] ?? '');
    $expected_close = trim($_POST['expected_close'] ?? ''); $status = trim($_POST['status'] ?? '');
    $stmt = $conn->prepare("UPDATE deals SET lead_id=?, deal_value=?, expected_close=?, status=? WHERE id=?");
    $stmt->bind_param("ssssi", $lead_id, $deal_value, $expected_close, $status, $id);
    if ($stmt->execute()) {
        $success = 'Deal updated!';
        $st2 = $conn->prepare("SELECT * FROM deals WHERE id = ?"); $st2->bind_param("i", $id); $st2->execute();
        $deal = $st2->get_result()->fetch_assoc(); $st2->close();
    } else { $error = 'Error: ' . $conn->error; }
    $stmt->close();
}
?>
<?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card">
    <div class="card-header">
        <h2>Edit Deal #<?= $id ?></h2>
        <a href="dashboard.php?page=view_deals" class="btn btn-outline btn-sm">Back to Deals</a>
    </div>
    <form method="POST" data-validate>
        <div class="form-row">
            <div class="form-group"><label for="lead_id">Lead</label>
                <select id="lead_id" name="lead_id" class="form-control" required>
                    <option value="">Select a lead</option>
                    <?php if ($leads_result): while ($lead = $leads_result->fetch_assoc()): ?>
                        <option value="<?= $lead['id'] ?>" <?= $deal['lead_id'] == $lead['id'] ? 'selected' : '' ?>><?= htmlspecialchars($lead['id'] . ' - ' . $lead['name']) ?></option>
                    <?php endwhile; endif; ?>
                </select>
            </div>
            <div class="form-group"><label for="deal_value">Deal Value</label><input type="text" id="deal_value" name="deal_value" class="form-control" value="<?= htmlspecialchars($deal['deal_value']) ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label for="expected_close">Expected Close</label><input type="date" id="expected_close" name="expected_close" class="form-control" value="<?= htmlspecialchars($deal['expected_close']) ?>"></div>
            <div class="form-group"><label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <?php foreach (['Open', 'Won', 'Lost', 'Pending'] as $opt): ?>
                        <option value="<?= $opt ?>" <?= $deal['status'] === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Deal</button>
    </form>
</div>