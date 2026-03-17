<?php
$success = '';
$error = '';
$users_result = $conn->query("SELECT id, name FROM sales_users ORDER BY name");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sales_user_id = trim($_POST['sales_user_id'] ?? '');
    $month = trim($_POST['month'] ?? '');
    $target_amount = trim($_POST['target_amount'] ?? '');
    if (empty($sales_user_id) || empty($month) || empty($target_amount)) {
        $error = 'All fields are required.';
    } else {
        $stmt = $conn->prepare("INSERT INTO targets (sales_user_id, month, target_amount) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $sales_user_id, $month, $target_amount);
        if ($stmt->execute()) { $success = 'Target added!'; }
        else { $error = 'Error: ' . $conn->error; }
        $stmt->close();
    }
}
?>
<?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card">
    <div class="card-header">
        <h2>Add New Target</h2>
        <span class="badge badge-info">INSERT</span>
    </div>
    <form method="POST" data-validate>
        <div class="form-row">
            <div class="form-group">
                <label for="sales_user_id">Sales User</label>
                <select id="sales_user_id" name="sales_user_id" class="form-control" required>
                    <option value="">Select a sales user</option>
                    <?php if ($users_result): while ($user = $users_result->fetch_assoc()): ?>
                        <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
                    <?php endwhile; endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="month">Month</label>
                <input type="text" id="month" name="month" class="form-control" placeholder="e.g. March 2026" required>
            </div>
        </div>
        <div class="form-group">
            <label for="target_amount">Target Amount</label>
            <input type="text" id="target_amount" name="target_amount" class="form-control" placeholder="Enter target amount" required>
        </div>
        <button type="submit" class="btn btn-primary">+ Add Target</button>
    </form>
</div>