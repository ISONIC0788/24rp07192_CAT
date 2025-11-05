<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] != 1) {
    header("Location: login.php");
    exit;
}

$book_id = $_GET['id'] ?? null;
if (!$book_id) {
    die("Book ID missing!");
}

// Delete book
$stmt = $mysqli->prepare("DELETE FROM books WHERE book_id = ?");
$stmt->bind_param('i', $book_id);

if ($stmt->execute()) {
    $_SESSION['msg'] = "Book deleted successfully!";
} else {
    $_SESSION['msg'] = "Failed to delete book.";
}

header("Location: admin_books.php");
exit;
?>
