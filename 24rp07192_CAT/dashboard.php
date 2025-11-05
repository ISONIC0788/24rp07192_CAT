<?php
require 'includes/db.php';
session_start();
if (empty($_SESSION['user'])) {
  header('Location: login.php'); exit;
}
$user = $_SESSION['user'];
require 'includes/header.php';

// show user info and borrowed books summary
$stmt = $mysqli->prepare("SELECT b.title, bb.borrow_date, bb.return_date, bb.status FROM borrowed_books bb JOIN books b ON bb.book_id=b.book_id WHERE bb.user_id=? ORDER BY bb.created_at DESC");
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$res = $stmt->get_result();
?>
<h2>Welcome, <?php echo htmlspecialchars($user['username']); ?></h2>
<p>Student ID: <?php
  $q = $mysqli->prepare("SELECT student_id FROM users WHERE user_id=? LIMIT 1");
  $q->bind_param('i', $user['id']); $q->execute(); $q->bind_result($sid); $q->fetch(); echo htmlspecialchars($sid);
?></p>

<div class="mb-3">
  <a href="books.php" class="btn btn-outline-primary">Browse Books</a>
  <a href="my_borrows.php" class="btn btn-outline-secondary">My Borrowed Books</a>
  <?php if($user['is_admin']): ?>
    <a href="admin_books.php" class="btn btn-warning">Admin: Manage Books</a>
  <?php endif; ?>
</div>

<h4>Your recent borrow requests</h4>
<table class="table table-striped">
  <thead><tr><th>Title</th><th>Borrow Date</th><th>Return Date</th><th>Status</th></tr></thead>
  <tbody>
    <?php while ($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($row['title']); ?></td>
        <td><?php echo htmlspecialchars($row['borrow_date']); ?></td>
        <td><?php echo htmlspecialchars($row['return_date'] ?? '-'); ?></td>
        <td><?php echo htmlspecialchars($row['status']); ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php require 'includes/footer.php'; ?>
