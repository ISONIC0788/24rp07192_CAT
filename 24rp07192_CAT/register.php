<?php
require 'includes/db.php';
session_start();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $student_id = trim($_POST['student_id'] ?? '');

    // Backend validation
    if (strlen($username) < 3) $errors[] = "Username must be at least 3 characters.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
    if (strlen($password) < 6) $errors[] = "Password must be >=6 characters.";
    if (empty($student_id)) $errors[] = "Student ID is required.";

    if (empty($errors)) {
        // Check duplicates
        $stmt = $mysqli->prepare("SELECT user_id FROM users WHERE email=? OR student_id=? LIMIT 1");
        $stmt->bind_param('ss', $email, $student_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Email or Student ID already registered.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $mysqli->prepare("INSERT INTO users (username,email,password,student_id) VALUES (?,?,?,?)");
            $ins->bind_param('ssss', $username, $email, $hash, $student_id);
            if ($ins->execute()) {
                $_SESSION['success'] = "Registration successful. Please login.";
                header('Location: login.php');
                exit;
            } else $errors[] = "Registration failed, try again.";
        }
    }
}
require 'includes/header.php';
?>

<div class="">
    <div class="row justify-content-center   ">
  <div class="col-md-6 card p-5">
    <h2>Register</h2>
    <?php if($errors): ?>
      <div class="alert alert-danger">
        <ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul>
      </div>
    <?php endif; ?>
    <form class="needs-validation" novalidate method="post" action="">
      <div class="mb-3">
        <label>Username</label>
        <input name="username" required class="form-control" minlength="3" />
        <div class="invalid-feedback">Enter username (min 3 chars).</div>
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input name="email" type="email" required class="form-control" />
        <div class="invalid-feedback">Enter a valid email.</div>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input name="password" type="password" required class="form-control" minlength="6" />
        <div class="invalid-feedback">Password must be at least 6 chars.</div>
      </div>
      <div class="mb-3">
        <label>Student ID</label>
        <input name="student_id" required class="form-control" />
        <div class="invalid-feedback">Student ID required.</div>
      </div>
      <button class="btn btn-primary">Register</button>
    </form>
  </div>
</div>


</div>


<?php require 'includes/footer.php'; ?>
