<?php
include 'db_conn.php';

// Fetch the post ID from the URL
$postId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($postId > 0) {
    // Fetch the full post based on the ID
    $query = "SELECT posts.*, categories.name AS category_name FROM posts INNER JOIN categories ON posts.category_id = categories.id WHERE posts.id = $postId";
    $result = mysqli_query($conn, $query);
    $post = mysqli_fetch_assoc($result);
} else {
    echo "Post not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($post['title']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            max-width: 1200px; /* Adjusted for wider layout */
            margin: 30px 30px;
            display: flex;
            flex-direction: row-reverse;
            justify-content: space-between; /* This will create space between the main content and the sidebar */
        }
        .main-content {
            width: 75%; /* Blog content takes 75% of the container width */
            padding-right: 30px; /* Space between content and sidebar */
        }
        .sidebar {
            width: 23%; /* Sidebar takes 23% of the container width */
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            
        }
        .post-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
            object-fit: cover;
            max-height: 400px;
        }
        h2.title {
            font-size: 28px;
            font-weight: bold;
            color: #343a40;
            text-align: center;
        }
        .meta-info, .category {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 20px;
        }
        .comment-box {
            background-color: rgb(247, 250, 252);
            border-radius: 20px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
            padding: 16px;
            margin: 8px 0;
        }
        .categories-title {
            font-size: 24px;
            font-weight: bold;
            color: #343a40;
            margin-bottom: 20px;
        }
        .categories-list {
            list-style-type: none;
            padding: 0;
        }
        .categories-list li {
            margin: 10px 0;
        }
        .categories-list a {
            text-decoration: none;
            color: #007bff;
            font-size: 18px;
            font-weight: 500;
            transition: color 0.3s;
        }
        .categories-list a:hover {
            color: #0056b3;
        }
        .form-control {
            font-size: 18px;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ced4da;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Main Content -->
    <div class="main-content">
        <!-- Blog Post Content -->
        <h2 class="title"><?= htmlspecialchars($post['title']); ?></h2>
        <p class="meta-info"><?= date('d M, Y', strtotime($post['created_at'])); ?></p>
        <p class="category"><strong>Category:</strong> <?= htmlspecialchars($post['category_name']); ?></p>
        <?php if (!empty($post['image'])): ?>
            <img src="upload/<?= htmlspecialchars($post['image']); ?>" alt="Post Image" class="post-image mb-4">
        <?php endif; ?>
        <div class="content">
            <p><?= strip_tags($post['content']); ?></p>
        </div>
        <a href="user.php" class="btn btn-primary mt-4">Back to Blog</a>

        <!-- Comment Form -->
        <h3 class="mt-5">Leave a Comment</h3>
        <form action="submit_comment.php" method="post">
            <input type="hidden" name="post_id" value="<?= $postId; ?>">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="comment">Comment:</label>
                <textarea name="comment" id="comment" class="form-control" rows="5" placeholder="Write your comment here..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Comment</button>
        </form>

        <!-- Display Comments Section -->
        <h3 class="mt-5">Comments</h3>
        <?php
        $commentQuery = "SELECT * FROM comments WHERE post_id = $postId AND is_visible = 1 ORDER BY created_at DESC";
        $commentResult = mysqli_query($conn, $commentQuery);
        if (mysqli_num_rows($commentResult) > 0):
            while ($comment = mysqli_fetch_assoc($commentResult)):
        ?>
            <div class="comment-box mb-4">
                <p class="comment-text"><?= nl2br(htmlspecialchars($comment['comment'])); ?></p>
                <p class="comment-meta">
                    <strong><?= htmlspecialchars($comment['name']); ?></strong> | 
                    <?= date('d M, Y H:i', strtotime($comment['created_at'])); ?>
                </p>
            </div>
        <?php
            endwhile;
        else:
            echo "<p>No comments yet. Be the first to comment!</p>";
        endif;
        ?>
    </div>

    <!-- Sidebar (Categories Dropdown) -->
    <div class="sidebar">
        <h3 class="categories-title">Categories</h3>

        <!-- Desktop Version -->
        <ul class="categories-list d-none d-md-block">
            <li><a href="/blog/alumni-student-stories/">Alumni &amp; Student Stories</a></li>
            <li><a href="/personal_blog/blog/Business/">Business</a></li>
            <li><a href="/blog/careers/">areers</a></li>
            <li><a href="/blog/education/">Education</a></li>
            <li><a href="/blog/health-sciences/">Health Sciences</a></li>
        </ul>

        <!-- Mobile Dropdown -->
        <select class="form-control d-md-none" onchange="location = this.value;">
            <option value="#">Select a Category</option>
            <option value="/blog/alumni-student-stories/">Alumni &amp; Student Stories</option>
            <option value="/blog/Business/">Business</option>
            <option value="/blog/careers/">Careers</option>
            <option value="/blog/education/">Education</option>
            <option value="/blog/health-sciences/">Health Sciences</option>
        </select>
    </div>
</div>

</body>
</html>
