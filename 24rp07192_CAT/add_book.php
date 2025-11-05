<?php
require 'includes/db.php';
session_start();
if (empty($_SESSION['user']) || !$_SESSION['user']['is_admin']) { header('Location: login.php'); exit; }
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $author = trim($_POST['author'] ?? '');
  $category = trim($_POST['category'] ?? '');
  if ($title === '' || $author === '') { $errors[] = "Title and author required."; }
  if (empty($errors)) {
    $ins = $mysqli->prepare("INSERT INTO books (title, author, category, availability) VALUES (?,?,?,1)");
    $ins->bind_param('sss', $title, $author, $category);
    if ($ins->execute()) {
      $_SESSION['success'] = "Book added.";
      header('Location: admin_books.php'); exit;
    } else $errors[] = "Failed to add books.";
  }
}
require 'includes/header.php';
?>
<div class="row   justify-content-center   ">
    <div class="col-md-10 "> 
        <h2>Add Book</h2>
<?php if($errors): ?><div class="alert alert-danger"><?=implode('<br>', $errors)?></div><?php endif; ?>
<form method="post" class="needs-validation col-md-7 card p-5" novalidate>
  <div class="mb-3">
    <label>Title</label>
  <input name="title" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Author</label>
  <input name="author" class="form-control" required>
</div>
  <div class="mb-3">
    <label>Category</label>
    <input name="category" class="form-control">
  </div>
  <button class="btn btn-success">Save</button>
</form>
    </div>

</div>

<?php require 'includes/footer.php'; ?>
