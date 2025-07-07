<?php
require_once 'db.php';

$id = $_GET['id'];

// Step 1: Delete related comments first
$pdo->prepare("DELETE FROM comments WHERE review_id = ?")->execute([$id]);

// Step 2: Now delete the review
$pdo->prepare("DELETE FROM reviews WHERE id = ?")->execute([$id]);

header("Location: index.php");
exit();
