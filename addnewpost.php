<?php 
include 'db_conn.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // echo '<pre>';
    // var_dump($_POST);
    // echo '</pre>';
    // die();
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = strip_tags($_POST['content'], '<p><br><b><i><u><a><img><ul><ol><li>'); 
    $content = mysqli_real_escape_string($conn, $content);

    $author = $_SESSION['username']; // Assuming author is logged-in user
    $category_id = $_POST['category_id'];
    $status = $_POST['status'];
    $created_at = date("Y-m-d H:i:s");

    // Handle Image Upload
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_folder = "upload/" . basename($image);
    move_uploaded_file($image_tmp, $image_folder);
    $target_dir= __DIR__ . "/upload";
    $target_file=$target_dir . basename($_FILES['image']['name']);

    // Insert into database
    $query = "INSERT INTO posts (title, content, author, category_id, image, status, created_at) 
              VALUES ('$title', '$content', '$author', '$category_id', '$image', '$status', '$created_at')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Post added successfully!'); window.location.href='manage_post.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.26.0/ui/trumbowyg.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.26.0/trumbowyg.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 250px;
            background: #333;
            color: white;
            height: 100vh;
            padding: 20px;
            position: fixed;
        }

        .sidebar h2 {
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 10px;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
        }

        .main-content {
            margin-left: 270px;
            flex-grow: 1;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Blog CMS</h2>
        <ul>
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="manage_comment.php">Manage Comments</a></li>
            <li><a href="manage_post.php">Manage Posts</a></li>
            <!-- <li><a href="users.php">Manage Users</a></li> -->
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-header text-secondary text-secondary text-center">
                            <h3>Add New Post</h3>
                        </div>
                        <div class="card-body">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Post Title</label>
                                    <input type="text" id="title" name="title" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="content" class="form-label">Content</label>
                                    <textarea id="content" name="content" class="form-control" rows="5" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select id="category" name="category_id" class="form-select" required>
                                        <option value="" selected disabled>Select Category</option>
                                        <?php
                                        
                                        $result = mysqli_query($conn, "SELECT * FROM categories");
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Upload Image</label>
                                    <input type="file" id="image" name="image" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select id="status" name="status" class="form-select" required>
                                        <option value="published">Published</option>
                                        <option value="draft">Draft</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success w-100">Submit Post</button>
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