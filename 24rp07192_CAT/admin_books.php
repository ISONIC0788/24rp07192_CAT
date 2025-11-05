<?php
require 'includes/db.php';
session_start();
if (empty($_SESSION['user']) || !$_SESSION['user']['is_admin']) { header('Location: login.php'); exit; }
require 'includes/header.php';

// get all books
$res = $mysqli->query("SELECT * FROM books ORDER BY title ASC");
?>
<h2>Admin: Manage Books</h2>
<a href="add_book.php" class="btn btn-success mb-3">Add Book</a>
<table class="table table-striped">
  <thead><tr><th>Title</th><th>Author</th><th>Category</th><th>Availability</th><th>Actions</th></tr></thead>
  <tbody>
    <?php while($b = $res->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($b['title']); ?></td>
        <td><?php echo htmlspecialchars($b['author']); ?></td>
        <td><?php echo htmlspecialchars($b['category']); ?></td>
        <td><?php echo $b['availability'] ? 'Available' : 'Unavailable'; ?></td>
        <td>
          <a class="btn btn-sm btn-primary" href="edit_book.php?id=<?php echo $b['book_id']; ?>">Edit</a>
          <a class="btn btn-sm btn-danger" href="delete_book.php?id=<?php echo $b['book_id']; ?>"
             onclick="return confirm('Delete this book?');">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php require 'includes/footer.php'; ?>
