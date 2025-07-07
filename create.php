<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $category_id = $_POST['category_id'];

    $imageName = '';
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageName);
    }

    $stmt = $pdo->prepare("INSERT INTO reviews (title, description, image, status, category_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $description, $imageName, $status, $category_id]);

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Add New Review</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Title:</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Description:</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Status:</label>
            <select name="status" class="form-control">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Category:</label>
            <select name="category_id" class="form-control">
                <?php
                $cat = $pdo->query("SELECT * FROM categories");
                while ($row = $cat->fetch()) {
                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>App Image:</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button class="btn btn-success">Submit</button>
        <a href="index.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>