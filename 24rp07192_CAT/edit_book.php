<?php
require 'includes/db.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] != 1) {
    header("Location: login.php");
    exit;
}

$book_id = $_GET['id'] ?? null;
if (!$book_id) {
    die("Book ID missing!");
}

// Fetch book info
$stmt = $mysqli->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->bind_param('i', $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();
if (!$book) {
    die("Book not found!");
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $availability = isset($_POST['availability']) ? 1 : 0;

    if (empty($title) || empty($author)) {
        $errors[] = "Title and Author are required.";
    }

    if (empty($errors)) {
        $update = $mysqli->prepare("UPDATE books SET title=?, author=?, category=?, availability=? WHERE book_id=?");
        $update->bind_param('sssii', $title, $author, $category, $availability, $book_id);
        if ($update->execute()) {
            $_SESSION['msg'] = "Book updated successfully!";
            header("Location: admin_books.php");
            exit;
        } else {
            $errors[] = "Update failed. Try again.";
        }
    }
}

require 'includes/header.php';
?>

<div class="row justify-content-center">
  <div class="col-md-6 card p-5">
    <h3>Edit Book</h3>
    <?php if($errors): ?>
      <div class="alert alert-danger"><ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul></div>
    <?php endif; ?>

    <form method="POST" class="needs-validation" novalidate>
      <div class="mb-3">
        <label>Title</label>
        <input name="title" value="<?= htmlspecialchars($book['title']) ?>" required class="form-control">
        <div class="invalid-feedback">Enter a title.</div>
      </div>

      <div class="mb-3">
        <label>Author</label>
        <input name="author" value="<?= htmlspecialchars($book['author']) ?>" required class="form-control">
        <div class="invalid-feedback">Enter author name.</div>
      </div>

      <div class="mb-3">
        <label>Category</label>
        <input name="category" value="<?= htmlspecialchars($book['category']) ?>" class="form-control">
      </div>

      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="availability" id="available" <?= $book['availability'] ? 'checked' : '' ?>>
        <label class="form-check-label" for="available">Available</label>
      </div>

      <button class="btn btn-primary">Save Changes</button>
      <a href="admin_books.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>

<?php require 'includes/footer.php'; ?>
