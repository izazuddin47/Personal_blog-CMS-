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

/* Navbar */
.navbar {
    background-color: #343a40;
    color: white;
}

.navbar .navbar-brand {
    color: white;
    font-size: 24px;
    font-weight: bold;
}

.navbar .navbar-nav .nav-item .nav-link {
    color: white;
    font-weight: 500;
    padding: 12px 15px;
}

.navbar .navbar-nav .nav-item .nav-link:hover {
    color: #ddd;
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
    padding: 15px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    width: 50%;
    margin-left: auto;
    margin-right: auto;
}

#categoryFilter {
    background-color: rgb(158, 156, 156);
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
    margin-bottom: 20px;
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
    width: 30px;
    height: 30px;
    object-fit: cover;
}

.d-flex.align-items-center {
    display: flex;
    align-items: center;
}

.author h6 {
    font-size: 14px;
    margin: 0;
}

/* Adjustments for equal space and responsive layout */
.blog-post {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.row {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.col-lg-4,
.col-md-6 {
    flex: 1;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .col-lg-4 {
        flex: 1 0 100%; /* Full width on smaller screens */
    }
}
</style>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">Blog CMS</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Category Filter -->
    <div class="filter-container mb-4">
        <form method="POST" action="">
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
    </div>

    <!-- Blog Posts Grid -->
    <div class="row">
        <?php foreach ($posts as $post): ?>
            <div class="col-lg-4 col-md-6">
                <div class="blog-post rounded border shadow-sm">
                    <!-- Blog Image -->
                    <div class="blog-img d-block overflow-hidden position-relative">
                        <img src="upload/<?= htmlspecialchars($post['image']); ?>" 
                             class="img-fluid rounded-top" alt="Post Image">
                    </div>
                    <!-- Blog Content -->
                    <div class="content p-3">
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
                        <a href="single-postuser.php?id=<?= $post['id']; ?>" class="btn btn-link p-0">
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
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function toggleContent(button) {
    const contentPreview = button.previousElementSibling.previousElementSibling;
    const contentFull = button.previousElementSibling;

    if (contentFull.style.display === "none") {
        // Show full content
        contentFull.style.display = "block";
        contentPreview.style.display = "none";
        button.textContent = "Read Less";
    } else {
        // Show preview
        contentFull.style.display = "none";
        contentPreview.style.display = "block";
        button.textContent = "Read More";
    }
}
</script>

</body>
</html>
