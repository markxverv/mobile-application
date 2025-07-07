<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_id = $_POST['review_id'];
    $comment = $_POST['comment'];

    $stmt = $pdo->prepare("INSERT INTO comments (review_id, comment) VALUES (?, ?)");
    $stmt->execute([$review_id, $comment]);

    header("Location: index.php");
    exit();
}
