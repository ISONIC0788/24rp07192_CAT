<?php
require 'includes/db.php';
session_start();
if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }
$user_id = $_SESSION['user']['id'];
require 'includes/header.php';

$stmt = $mysqli->prepare("SELECT bb.id, b.title, bb.borrow_date, bb.return_date, bb.status FROM borrowed_books bb JOIN books b ON bb.book_id=b.book_id WHERE bb.user_id=? ORDER BY bb.created_at DESC");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$res = $stmt->get_result();
?>
<h2>My Borrowed Books</h2>
<table class="table">
  <thead>
    <tr>
        <th>Title</th>
        <th>Borrow Date</th>
        <th>Return Date</th>
        <th>Status</th></tr>
    </thead>
  <tbody>
  <?php while($r = $res->fetch_assoc()): ?>
    <tr>
      <td><?php echo htmlspecialchars($r['title']); ?></td>
      <td><?php echo htmlspecialchars($r['borrow_date']); ?></td>
      <td><?php echo htmlspecialchars($r['return_date'] ?? '-'); ?></td>
      <td><?php echo htmlspecialchars($r['status']); ?></td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>
<?php require 'includes/footer.php'; ?>
