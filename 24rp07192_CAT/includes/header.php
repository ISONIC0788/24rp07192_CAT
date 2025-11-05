<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RP Karongi Library</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
  <div class="container">
    <a class="navbar-brand" href="/rp_karongi_lib/index.php">RP Karongi Library</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"
      aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="/24rp07192_CAT/index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="/24rp07192_CAT/register.php">Register</a></li>
        <li class="nav-item"><a class="nav-link" href="/24rp07192_CAT/login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="/24rp07192_CAT/books.php">Books</a></li>
        <li class="nav-item"><a class="nav-link" href="/24rp07192_CAT/index.php#contact">Contact</a></li>
        <?php if(!empty($_SESSION['user'])): ?>
          <li class="nav-item"><a class="nav-link" href="/24rp07192_CAT/dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="/24rp07192_CAT/logout.php">Logout</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container py-4">

<script src="/assets/js/bootstrap.bundle.min.js"></script>

