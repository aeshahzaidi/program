<?php 
session_start();
include 'connect.php';

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$message = "";
$username = "";

// Fetch username
$sql_user = "SELECT username FROM user WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$stmt_user->bind_result($username);
$stmt_user->fetch();
$stmt_user->close();

// Handle password update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = trim($_POST['current_password']);
    $new     = trim($_POST['new_password']);
    $confirm = trim($_POST['confirm_password']);

    // Fetch current password hash
    $sql = "SELECT password FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($db_password);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current, $db_password)) {
        $message = '<div class="alert alert-danger">Current password is incorrect!</div>';
    } elseif ($new !== $confirm) {
        $message = '<div class="alert alert-warning">New passwords do not match!</div>';
    } elseif (strlen($new) < 6) {
        $message = '<div class="alert alert-warning">Password must be at least 6 characters long!</div>';
    } else {
        $new_hash = password_hash($new, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE user SET password = ? WHERE id = ?");
        $update->bind_param("si", $new_hash, $user_id);
        if ($update->execute()) {
            $message = '<div class="alert alert-success">Password updated successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">Something went wrong. Try again later!</div>';
        }
        $update->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Change Password</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    include 'navbar_admin.php';
} else {
    include 'navbar_user.php';
}
?>


<style>

.change-password-page {
    background-color: #f8f9fa;
    min-height: 100vh;
    padding-top: 40px;
}

.change-password-page .password-card {
    max-width: 480px;
    margin: 60px auto;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    background: #fff;
}

.change-password-page .form-label {
    font-weight: 500;
}

.change-password-page .btn-custom {
    width: 100%;
    border-radius: 8px;
    padding: 10px;
}

.change-password-page .username-box {
    background: #f1f1f1;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 15px;
    font-weight: 500;
    text-align: center;
}
</style>

<div class="change-password-page">
  <div class="password-card">
    <h4 class="mb-3 text-center">Change Password</h4>

    <div class="username-box">
      <span class="text-primary"><?php echo htmlspecialchars($username); ?></span>
    </div>

    <?php echo $message; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label class="form-label">Current Password</label>
        <input type="password" name="current_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" name="new_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Confirm New Password</label>
        <input type="password" name="confirm_password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary btn-custom">Update Password</button>
    </form>
  </div>
</div>

</body>
</html>
