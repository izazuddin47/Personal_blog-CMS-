<?php
include 'db_conn.php';

// Check if post ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid Post ID.'); window.location.href='index.php';</script>";
    exit;
}

$post_id = intval($_GET['id']);

// Fetch the existing post details
$query = "SELECT * FROM posts WHERE id = $post_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "<script>alert('Error fetching post: " . mysqli_error($conn) . "'); window.location.href='index.php';</script>";
    exit;
}

$post = mysqli_fetch_assoc($result);

if (!$post) {
    echo "<script>alert('Post not found.'); window.location.href='index.php';</script>";
    exit;
}

// Fetch categories for dropdown
$categoryQuery = "SELECT * FROM categories";
$categoryResult = mysqli_query($conn, $categoryQuery);
$categories = mysqli_fetch_all($categoryResult, MYSQLI_ASSOC);

// Handle form submission (Updating Post)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $category_id = intval($_POST['category_id']);

    // Handle Image Upload (if a new image is selected)
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "upload/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    } else {
        $image = $post['image']; // Keep the old image if not changed
    }

    // Update Query
    $updateQuery = "UPDATE posts SET title='$title', content='$content', category_id='$category_id', image='$image' WHERE id=$post_id";
    
    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Post updated successfully!'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Error updating post.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.26.0/trumbowyg.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.26.0/ui/trumbowyg.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .main-content {
            margin-left: 270px;
            flex-grow: 1;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-header text-secondary text-center">
                            <h3>Update Post</h3>
                        </div>
                        <div class="card-body">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Post Title</label>
                                    <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($post['title']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="content" class="form-label">Content</label>
                                    <textarea id="content" name="content" class="form-control" rows="5" required><?= htmlspecialchars($post['content']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select id="category" name="category_id" class="form-select" required>
                                        <option value="" selected disabled>Select Category</option>
                                        <?php
                                        $result = mysqli_query($conn, "SELECT * FROM categories");
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $selected = ($post['category_id'] == $row['id']) ? 'selected' : '';
                                            echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Update Image</label>
                                    <input type="file" id="image" name="image" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select id="status" name="status" class="form-select" required>
                                        <option value="published" <?= $post['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                                        <option value="draft" <?= $post['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success w-100">Update Post</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function () {
        $('#content').trumbowyg({
            removeformatPasted: true // Automatically removes HTML on paste
        });
    });
    </script>

</body>
</html>
