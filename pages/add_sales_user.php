<?php
$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if (empty($name) || empty($email)) {
        $error = 'Name and email are required.';
    } else {
        $stmt = $conn->prepare("INSERT INTO sales_users (name, email, mobile, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $mobile, $password);
        if ($stmt->execute()) { $success = 'Sales user added!'; }
        else { $error = 'Error: ' . $conn->error; }
        $stmt->close();
    }
}
?>
<?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card">
    <div class="card-header">
        <h2>Add New Sales User</h2>
        <span class="badge badge-info">INSERT</span>
    </div>
    <form method="POST" data-validate>
        <div class="form-row">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Enter full name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter email" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="mobile">Mobile</label>
                <input type="text" id="mobile" name="mobile" class="form-control" placeholder="Enter mobile number">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter password">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">+ Add Sales User</button>
    </form>
</div>