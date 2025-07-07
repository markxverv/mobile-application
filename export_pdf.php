<?php
require 'vendor/autoload.php';
require 'db.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$html = '<h2>Mobile Application Reviews</h2><hr>';

$stmt = $pdo->query("SELECT r.*, c.name AS category 
                     FROM reviews r 
                     JOIN categories c ON r.category_id = c.id");

while ($row = $stmt->fetch()) {
    $html .= "
    <p><strong>Title:</strong> " . htmlspecialchars($row['title']) . "<br>
    <strong>Category:</strong> " . htmlspecialchars($row['category']) . "<br>
    <strong>Status:</strong> " . htmlspecialchars($row['status']) . "<br>
    <strong>Description:</strong> " . htmlspecialchars($row['description']) . "<br>
    <strong>Created:</strong> " . date('d M Y, h:i A', strtotime($row['created_at'])) . "</p>
    <hr>";
}

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("mobile_reviews.pdf", ["Attachment" => false]);
