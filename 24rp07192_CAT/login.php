<?php
require 'includes/db.php';
session_start();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
    if (empty($password)) $errors[] = "Enter password.";

    if (empty($errors)) {
        $stmt = $mysqli->prepare("SELECT user_id, username, password, is_admin FROM users WHERE email=? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($user_id, $username, $hash, $is_admin);
        if ($stmt->fetch()) {
            if (password_verify($password, $hash)) {
                session_regenerate_id(true);
                $_SESSION['user'] = [
                  'id' => $user_id,
                  'username' => $username,
                  'is_admin' => (int)$is_admin
                ];
                header('Location: dashboard.php');
                exit;
            } else {
                $errors[] = "Wrong email or password.";
            }
        } else {
            $errors[] = "Wrong email or password.";
        }
    }
}
require 'includes/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-5 card p-5">
    <h2>Login</h2>
    <?php if(!empty($_SESSION['success'])) { echo "<div class='alert alert-success'>".$_SESSION['success']."</div>"; unset($_SESSION['success']); } ?>
    <?php if($errors): ?>
      <div class="alert alert-danger"><ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul></div>
    <?php endif; ?>
    <form class="needs-validation" novalidate method="post" action="">
      <div class="mb-3">
        <label>Email</label>
        <input name="email" type="email" required class="form-control" />
        <div class="invalid-feedback">Provide email.</div>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input name="password" type="password" required class="form-control" />
        <div class="invalid-feedback">Provide password.</div>
      </div>
      <button class="btn btn-primary">Login</button>
    </form>
  </div>
</div>
<?php require 'includes/footer.php'; ?>
