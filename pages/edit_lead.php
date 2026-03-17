<?php
$success = '';
$error = '';
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { echo '<div class="alert alert-danger">Invalid lead ID.</div>'; return; }
$sales_users_result = $conn->query("SELECT id, name FROM sales_users ORDER BY name");
$stmt = $conn->prepare("SELECT * FROM leads WHERE id = ?");
$stmt->bind_param("i", $id); $stmt->execute();
$lead = $stmt->get_result()->fetch_assoc(); $stmt->close();
if (!$lead) { echo '<div class="alert alert-danger">Lead not found.</div>'; return; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? ''); $mobile = trim($_POST['mobile'] ?? '');
    $email = trim($_POST['email'] ?? ''); $source = trim($_POST['source'] ?? '');
    $status = trim($_POST['status'] ?? ''); $assigned_to = trim($_POST['assigned_to'] ?? '');
    if (empty($name) || empty($mobile)) { $error = 'Name and mobile are required.'; }
    else {
        $stmt = $conn->prepare("UPDATE leads SET name=?, mobile=?, email=?, source=?, status=?, assigned_to=? WHERE id=?");
        $stmt->bind_param("ssssssi", $name, $mobile, $email, $source, $status, $assigned_to, $id);
        if ($stmt->execute()) {
            $success = 'Lead updated!';
            $st2 = $conn->prepare("SELECT * FROM leads WHERE id = ?"); $st2->bind_param("i", $id); $st2->execute();
            $lead = $st2->get_result()->fetch_assoc(); $st2->close();
        } else { $error = 'Error: ' . $conn->error; }
        $stmt->close();
    }
}
?>
<?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card">
    <div class="card-header">
        <h2>Edit Lead #<?= $id ?></h2>
        <a href="dashboard.php?page=view_leads" class="btn btn-outline btn-sm">Back to Leads</a>
    </div>
    <form method="POST" data-validate>
        <div class="form-row">
            <div class="form-group"><label for="name">Name</label><input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($lead['name']) ?>" required></div>
            <div class="form-group"><label for="mobile">Mobile</label><input type="text" id="mobile" name="mobile" class="form-control" value="<?= htmlspecialchars($lead['mobile']) ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label for="email">Email</label><input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($lead['email']) ?>"></div>
            <div class="form-group"><label for="source">Source</label>
                <select id="source" name="source" class="form-control">
                    <?php foreach (['', 'Website', 'Referral', 'Social Media', 'Cold Call', 'Email Campaign', 'Other'] as $opt): ?>
                        <option value="<?= $opt ?>" <?= $lead['source'] === $opt ? 'selected' : '' ?>><?= $opt ?: 'Select source' ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group"><label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <?php foreach (['New', 'Contacted', 'Qualified', 'Converted', 'Lost'] as $opt): ?>
                        <option value="<?= $opt ?>" <?= $lead['status'] === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label for="assigned_to">Assigned To</label>
                <select id="assigned_to" name="assigned_to" class="form-control">
                    <option value="">Unassigned</option>
                    <?php if ($sales_users_result): while ($user = $sales_users_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($user['name']) ?>" <?= $lead['assigned_to'] === $user['name'] ? 'selected' : '' ?>><?= htmlspecialchars($user['name']) ?></option>
                    <?php endwhile; endif; ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Lead</button>
    </form>
</div>