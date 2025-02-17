<?php
include 'db_conn.php';

// Fetch categories for the filter menu
$categoryQuery = "SELECT * FROM categories";
$categoryResult = mysqli_query($conn, $categoryQuery);

$categories = [];
while ($row = mysqli_fetch_assoc($categoryResult)) {
    $categories[] = $row;
}

// Fetch all blog posts or filter by category
$selectedCategory = isset($_POST['category']) ? $_POST['category'] : '';
$query = "SELECT posts.*, categories.name AS category_name FROM posts INNER JOIN categories ON posts.category_id = categories.id";
if (!empty($selectedCategory)) {
    $query .= " WHERE categories.id = " . intval($selectedCategory);
}
$result = mysqli_query($conn, $query);

$posts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $posts[] = $row;
}

if (isset($_GET['delete_id']) && !empty($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    // Delete the post from the database
    $deleteQuery = "DELETE FROM posts WHERE id = $delete_id";
    if (mysqli_query($conn, $deleteQuery)) {
        echo "<script>alert('Post deleted successfully!'); window.location.href='manage_post.php';</script>";
    } else {
        echo "<script>alert('Error deleting post.'); window.location.href='manage_post.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Posts</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css">
</head>

<style>
body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    background-color: #f8f9fa;
    color: #333;
}

.sidebar {
    width: 250px;
    background: #343a40;
    color: white;
    height: 100vh;
    padding: 20px;
    position: fixed;
    top: 0;
    left: 0;
    overflow-y: auto;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
}

.sidebar h2 {
    text-align: center;
    font-size: 24px;
    margin-bottom: 20px;
}

.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar ul li {
    padding: 12px;
    border-bottom: 1px solid #495057;
}

.sidebar ul li a {
    color: white;
    text-decoration: none;
    display: block;
    font-weight: 500;
    transition: color 0.3s ease;
}

.sidebar ul li a:hover {
    color: #f8d7da;
}

.container {
    margin-left: 270px;
    padding: 40px 20px;
}

.title {
    font-size: 28px;
    color: #343a40;
    font-weight: bold;
}

.filter-container {
    background-color:rgb(126, 126, 126);
    padding: 15px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

#categoryFilter {
    background-color:rgb(158, 156, 156);
    padding: 8px 12px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 100%;
    max-width: 300px;
}

.blog-post {
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.blog-post:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.blog-img img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.content {
    padding: 15px;
}

.content h4 {
    font-size: 20px;
    color: #343a40;
}

.content p {
    color: #6c757d;
    font-size: 14px;
}

.avatar-sm {
    width: 30px; /* Small profile photo size */
    height: 30px;
    object-fit: cover; /* Ensures image fits within the circle */
}

.d-flex.align-items-center {
    display: flex;
    align-items: center; /* Ensures vertical alignment */
}

.author h6 {
    font-size: 14px; /* Adjust font size for better proportion */
    margin: 0; /* Remove default margins */
}

/* Equal height for all posts */
.row > .col-lg-4 {
    display: flex;
    justify-content: center;
    align-items: stretch;
}

</style>

<body>
<div class="sidebar">
        <h2>Blog CMS</h2>
        <ul>
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="categories.php">Manage Categories</a></li>
            <li><a href="manage_comment.php">Manage Comments</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="container mt-0">
        <!-- Category Filter -->
        <div class="filter-container mb-4" style="display: flex; justify-content: space-between; align-items: center;">
            <form method="POST" action="" style="flex-grow: 1;">
                <label for="categoryFilter">Filter by Category:</label>
                <select id="categoryFilter" name="category" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['id']); ?>" 
                            <?= ($selectedCategory == $category['id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>

            <a href="addnewpost.php" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 16px; font-weight: bold; text-align: center; transition: background-color 0.3s;">
                Add New Blog
            </a>
        </div>

        <!-- Blog Posts Grid -->
        <div class="row">
            <?php foreach ($posts as $post): ?>
                <div class="col-lg-4 col-md-6 mt-4 pt-2">
                    <div class="blog-post rounded border shadow-sm d-flex flex-column">
                        
                        <!-- Blog Image -->
                        <div class="blog-img d-block overflow-hidden position-relative">
                            <img src="upload/<?= htmlspecialchars($post['image']); ?>" 
                                 class="img-fluid rounded-top" alt="Post Image">
                        </div>
                        
                        <!-- Blog Content -->
                        <div class="content p-3 flex-grow-1">
                            <small class="text-muted float-right">
                                <?= date('d M, Y', strtotime($post['created_at'])); ?>
                            </small>
                            <small>
                                <a href="#" class="text-primary">
                                    <?= htmlspecialchars($post['category_name']); ?>
                                </a>
                            </small>
                            <h4 class="mt-2">
                                <a href="single-post.php?id=<?= $post['id']; ?>" 
                                   class="text-dark title"><?= htmlspecialchars($post['title']); ?>
                                </a>
                            </h4>
                            <p class="text-muted mt-2">
                                <?= strip_tags(substr($post['content'], 0, 100)); ?>...
                            </p>
                            
                            <!-- Read More Button -->
                            <a href="single-post.php?id=<?= $post['id']; ?>" class="btn btn-link p-0">
                                Read More
                            </a>
                        </div>
                        
                        <!-- Author Info -->
                        <div class="pt-3 mt-3 border-top d-flex align-items-center">
                            <img src="https://bootdey.com/img/Content/avatar/avatar2.png" 
                                 class="img-fluid avatar avatar-sm rounded-circle mr-2 shadow" 
                                 alt="Profile Photo">
                            <div class="author">
                                <h6 class="mb-0">
                                    <a href="#" class="text-dark name">
                                        <?= $_SESSION['name']; ?>
                                    </a>
                                </h6>
                            </div>
                        </div>

                        <!-- Update and Delete Buttons -->
                        <div class="button-container mt-3">
                            <a href="update_post.php?id=<?= $post['id']; ?>" class="btn btn-warning" style="margin-right: 10px;">
                                Update
                            </a>
                            
                            <a href="?delete_id=<?= $post['id']; ?>" class="btn btn-danger"
                               onclick="return confirm('Are you sure you want to delete this post?');">
                                Delete
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</body>
</html>
