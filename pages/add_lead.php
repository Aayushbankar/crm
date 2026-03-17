<?php
$success = '';
$error = '';
$sales_users_result = $conn->query("SELECT id, name FROM sales_users ORDER BY name");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $source = trim($_POST['source'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $assigned_to = trim($_POST['assigned_to'] ?? '');
    $created_at = date('Y-m-d H:i:s');
    if (empty($name) || empty($mobile)) {
        $error = 'Name and mobile are required.';
    } else {
        $stmt = $conn->prepare("INSERT INTO leads (name, mobile, email, source, status, assigned_to, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $name, $mobile, $email, $source, $status, $assigned_to, $created_at);
        if ($stmt->execute()) { $success = 'Lead added!'; }
        else { $error = 'Error: ' . $conn->error; }
        $stmt->close();
    }
}
?>
<?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card">
    <div class="card-header">
        <h2>Add New Lead</h2>
        <span class="badge badge-info">INSERT</span>
    </div>
    <form method="POST" data-validate>
        <div class="form-row">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Enter lead name" required>
            </div>
            <div class="form-group">
                <label for="mobile">Mobile</label>
                <input type="text" id="mobile" name="mobile" class="form-control" placeholder="Enter mobile number" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter email address">
            </div>
            <div class="form-group">
                <label for="source">Source</label>
                <select id="source" name="source" class="form-control">
                    <option value="">Select source</option>
                    <option value="Website">Website</option>
                    <option value="Referral">Referral</option>
                    <option value="Social Media">Social Media</option>
                    <option value="Cold Call">Cold Call</option>
                    <option value="Email Campaign">Email Campaign</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="New">New</option>
                    <option value="Contacted">Contacted</option>
                    <option value="Qualified">Qualified</option>
                    <option value="Converted">Converted</option>
                    <option value="Lost">Lost</option>
                </select>
            </div>
            <div class="form-group">
                <label for="assigned_to">Assigned To</label>
                <select id="assigned_to" name="assigned_to" class="form-control">
                    <option value="">Unassigned</option>
                    <?php if ($sales_users_result): while ($user = $sales_users_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($user['name']) ?>"><?= htmlspecialchars($user['name']) ?></option>
                    <?php endwhile; endif; ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">+ Add Lead</button>
    </form>
</div>