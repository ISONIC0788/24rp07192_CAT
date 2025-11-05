<?php
require 'includes/db.php';
session_start();
if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }

$user_id = $_SESSION['user']['id'];
$book_id = intval($_POST['book_id'] ?? 0);
if ($book_id <= 0) {
  $_SESSION['error'] = "Invalid book.";
  header('Location: books.php'); exit;
}


$s = $mysqli->prepare("SELECT availability FROM books WHERE book_id=? LIMIT 1");
$s->bind_param('i', $book_id); $s->execute(); $s->bind_result($availability);
if (!$s->fetch()) { $_SESSION['error'] = "Book not found."; header('Location: books.php'); exit; }
$s->close();

if (!$availability) {
  $_SESSION['error'] = "Book is currently unavailable."; 
  header('Location: books.php'); exit;
}


$mysqli->begin_transaction();
try {
  $today = date('Y-m-d');
  $ins = $mysqli->prepare("INSERT INTO borrowed_books (user_id, book_id, borrow_date, status) VALUES (?,?,?, 'borrowed')");
 
  $ins->bind_param('iis', $user_id, $book_id, $today); $ins->execute();

  $upd = $mysqli->prepare("UPDATE books SET availability=0 WHERE book_id=?");
  $upd->bind_param('i', $book_id); $upd->execute();

  $mysqli->commit();
  $_SESSION['success'] = "Book borrowed successfully.";
} catch (Exception $e) {
  $mysqli->rollback();
  $_SESSION['error'] = "Operation failed: " . $e->getMessage();
}
header('Location: dashboard.php');
