<?php
require 'includes/db.php';
session_start();
require 'includes/header.php';

// search/filter
$search = trim($_GET['q'] ?? '');
$category = trim($_GET['category'] ?? '');

$sql = "SELECT book_id, title, author, category, availability FROM books WHERE 1=1";
$params = [];
$types = '';

if ($search !== '') {
  $sql .= " AND (title LIKE CONCAT('%',?,'%') OR author LIKE CONCAT('%',?,'%'))";
  $params[] = $search; $params[] = $search; $types .= 'ss';
}
if ($category !== '') {
  $sql .= " AND category = ?";
  $params[] = $category; $types .= 's';
}
$sql .= " ORDER BY title ASC LIMIT 200";

$stmt = $mysqli->prepare($sql);
if ($params) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Available Books</h2>

<form class="row g-2 mb-3" method="get">
  <div class="col-md-4">
    <input class="form-control" name="q" placeholder="Search title or author" value="<?php echo htmlspecialchars($search); ?>">
  </div>
  <div class="col-md-3">
    <input class="form-control" name="category" placeholder="Category" value="<?php echo htmlspecialchars($category); ?>">
  </div>
  <div class="col-md-2">
    <button class="btn btn-primary">Search</button>
  </div>
</form>

<table class="table table-bordered table-hover">
  <thead><tr><th>Title</th><th>Author</th><th>Category</th><th>Availability</th><th>Action</th></tr></thead>
  <tbody>
  <?php while($b = $result->fetch_assoc()): ?>
    <tr>
      <td><?php echo htmlspecialchars($b['title']); ?></td>
      <td><?php echo htmlspecialchars($b['author']); ?></td>
      <td><?php echo htmlspecialchars($b['category']); ?></td>
      <td><?php echo $b['availability'] ? '<span class="badge bg-success">Available</span>' : '<span class="badge bg-danger">Unavailable</span>'; ?></td>
      <td>
        <?php if($b['availability']): ?>
          <?php if(isset($_SESSION['user'])): ?>
            <form method="post" action="borrow_action.php" class="d-inline">
              <input type="hidden" name="book_id" value="<?php echo $b['book_id']; ?>">
              <button class="btn btn-sm btn-primary">Request Borrow</button>
            </form>
          <?php else: ?>
            <a href="login.php" class="btn btn-sm btn-outline-primary">Login to borrow</a>
          <?php endif; ?>
        <?php else: ?>
          <button class="btn btn-sm btn-secondary" disabled>Not available</button>
        <?php endif; ?>
      </td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>

<?php require 'includes/footer.php'; ?>
