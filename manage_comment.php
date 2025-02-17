<?php
include 'db_conn.php'; // Database connection

// Handle comment deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $comment_id = $_POST['delete_id'];

    $query = "DELETE FROM comments WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $comment_id);
    mysqli_stmt_execute($stmt);
}

// Fetch comments with blog post title
$query = "SELECT comments.id, comments.post_id, comments.comment, posts.title 
          FROM comments 
          JOIN posts ON comments.post_id = posts.id 
          ORDER BY comments.id DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<style>
    body {
        display: flex;
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

    .sidebar ul li a:hover {
        background-color: #555;
        border-radius: 5px;
    }

    .main-content {
        flex-grow: 1;
        padding: 20px;
    }

    .table {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .table th, .table td {
        padding: 12px;
        text-align: left;
    }

    .table th {
        background-color: #343a40;
        color: white;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
        font-weight: bold;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }
</style>


<div class="sidebar">
        <h2>Blog CMS</h2>
        <ul>
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="categories.php">Manage Categories</a></li>
            <li><a href="manage_post.php">Manage Post</a></li>
            <li><a href="manage_comment.php">Manage Comments</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

   <div class="main-content">
    <h2>Manage Comments</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Blog ID</th>
                <th>Blog Title</th>
                <th>Comment</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) { ?>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['post_id']; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['comment']; ?></td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Are you sure?');">
                                <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="5">No comments found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
