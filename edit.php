<?php
require_once 'db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM reviews WHERE id = ?");
$stmt->execute([$id]);
$review = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $category_id = $_POST['category_id'];

    $imageName = $review['image'];
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageName);
    }

    $update = $pdo->prepare("UPDATE reviews SET title=?, description=?, image=?, status=?, category_id=? WHERE id=?");
    $update->execute([$title, $description, $imageName, $status, $category_id, $id]);

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Edit Review</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Title:</label>
            <input type="text" name="title" class="form-control" value="<?= $review['title'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Description:</label>
            <textarea name="description" class="form-control"><?= $review['description'] ?></textarea>
        </div>

        <div class="mb-3">
            <label>Status:</label>
            <select name="status" class="form-control">
                <option value="active" <?= $review['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $review['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Category:</label>
            <select name="category_id" class="form-control">
                <?php
                $cat = $pdo->query("SELECT * FROM categories");
                while ($row = $cat->fetch()) {
                    $sel = $row['id'] == $review['category_id'] ? 'selected' : '';
                    echo "<option value='{$row['id']}' $sel>{$row['name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>App Image:</label>
            <input type="file" name="image" class="form-control">
            <p>Current: <img src="uploads/<?= $review['image'] ?>" height="50"></p>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="index.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
