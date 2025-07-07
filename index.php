<?php
require_once 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mobile Application Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        h2 {
            font-weight: bold;
            color: #343a40;
        }

        .card {
            border-radius: 1rem;
        }

        .card-title {
            color: #007bff;
            font-weight: 600;
        }

        .btn-sm {
            font-size: 0.8rem;
        }

        .input-group-sm input {
            border-radius: 0.5rem 0 0 0.5rem;
        }

        .btn-outline-primary {
            border-radius: 0 0.5rem 0.5rem 0;
        }

        .badge {
            font-size: 0.75rem;
        }

        .text-muted.small {
            margin-bottom: 0.3rem;
        }

        .card-img-top {
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }
    </style>
</head>
<body>

<div class="container py-4">
    <h2 class="text-center mb-4">📱 Mobile Application Reviews</h2>

    <!-- Search Bar -->
    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by title or category" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <!-- Action Buttons -->
    <div class="mb-4 d-flex justify-content-between">
        <a href="create.php" class="btn btn-success">➕ Add New Review</a>
        <a href="export_pdf.php" class="btn btn-danger">📄 Export to PDF</a>
    </div>

    <!-- Reviews Grid -->
    <div class="row">
        <?php
        $query = "SELECT r.*, c.name AS category 
                  FROM reviews r 
                  JOIN categories c ON r.category_id = c.id";

        if (!empty($_GET['search'])) {
            $search = '%' . $_GET['search'] . '%';
            $stmt = $pdo->prepare($query . " WHERE r.title LIKE ? OR c.name LIKE ?");
            $stmt->execute([$search, $search]);
        } else {
            $stmt = $pdo->prepare($query);
            $stmt->execute();
        }

        while ($row = $stmt->fetch()) {
        ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <!-- App Image -->
                <img src="uploads/<?= htmlspecialchars($row['image']) ?>" class="card-img-top" alt="App Image" style="height: 200px; object-fit: cover;">

                <!-- Card Body -->
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($row['description']) ?></p>
                    <p><strong>Category:</strong> <?= htmlspecialchars($row['category']) ?></p>
                    <p><strong>Status:</strong>
                        <?php if ($row['status'] === 'active'): ?>
                            <span class="badge bg-success">Active</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inactive</span>
                        <?php endif; ?>
                    </p>
                    <p><strong>Created:</strong> <?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></p>

                    <!-- Edit/Delete Buttons -->
                    <div class="d-flex gap-2 mb-2">
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">✏️ Edit</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">🗑 Delete</a>
                    </div>

                    <hr>

                    <!-- Comments Section -->
                    <h6>💬 Comments:</h6>
                    <?php
                    $commentStmt = $pdo->prepare("SELECT * FROM comments WHERE review_id = ?");
                    $commentStmt->execute([$row['id']]);
                    while ($comment = $commentStmt->fetch()) {
                        echo "<p class='text-muted small'>– " . htmlspecialchars($comment['comment']) . "</p>";
                    }
                    ?>

                    <!-- Comment Form -->
                    <form method="POST" action="add_comment.php" class="mt-2">
                        <div class="input-group input-group-sm">
                            <input type="hidden" name="review_id" value="<?= $row['id'] ?>">
                            <input type="text" name="comment" class="form-control" placeholder="Write a comment..." required>
                            <button type="submit" class="btn btn-outline-primary">Post</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<!-- Bootstrap JS Bundle (optional for interactivity) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
