<?php
include 'db_conn.php';



if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}



// Fetch total posts
$totalPosts = $conn->query("SELECT COUNT(*) FROM posts")->fetch_row()[0];

// Fetch total comments
$totalComments = $conn->query("SELECT COUNT(*) FROM comments")->fetch_row()[0];

// Fetch total users
$totalUsers = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];

$totalcategories = $conn->query("SELECT COUNT(*) FROM categories")->fetch_row()[0];






?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <meta name="description" content="Welcome to [Your Blog Name], where we share articles on [general topics]. Stay updated with our latest posts!">
<meta name="keywords" content="blog, articles, [general topics like business, health, etc.]">

<style>
    body {
        display: flex;
        font-family: 'Arial', sans-serif;
        margin: 0;
        background-color: #f9f9f9;
    }

    .sidebar {
        width: 250px;
        background: #333;
        color: white;
        height: 100vh;
        padding: 15px;
    }

    .sidebar h2 {
        text-align: center;
        font-size: 30px;
        margin-bottom: 20px;
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
        font-size: 30px;
    
    }

    .sidebar ul li {
        padding: 10px;
        font-size: 16px;
    }

    .sidebar ul li a {
        color: white;
        text-decoration: none;
        display: block;
        transition: background-color 0.3s ease;
    }

    .sidebar ul li a:hover {
        background-color: #444;
    }

    .main-content {
        flex-grow: 1;
        padding: 20px;
    }

    h1 {
        font-size: 28px;
        margin-bottom: 20px;
    }

    .stats {
        display: flex;
        gap: 15px;
        justify-content: space-between;
    }

    .stat-box {
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        width: 22%;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .stat-box h3 {
        font-size: 18px;
        color: #333;
    }

    .stat-box p {
        font-size: 24px;
        font-weight: bold;
        color: #007bff;
    }

    @media (max-width: 768px) {
        .stats {
            flex-direction: column;
            align-items: center;
        }

        .stat-box {
            width: 80%;
            margin-bottom: 15px;
        }
    }
</style>


</head>
<body>
    <div class="sidebar">
        <h2>Blog CMS</h2>
        <ul>
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="categories.php">Manage Categories</a></li>
            <li><a href="manage_post.php">Manage Post</a></li>
            <li><a href="manage_comment.php">Manage Comments</a></li>
            <!-- <li><a href="user.php">Manage Users</a></li> -->
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <h1>Dashboard</h1>
        <div class="stats">
            <div class="stat-box">
                <h3>Total Posts</h3>
                <p><?php echo $totalPosts; ?></p>
            </div>
            <div class="stat-box">
                <h3>Total Comments</h3>
                <p><?php echo $totalComments; ?></p>
            </div>
            <div class="stat-box">
                <h3>Total Users</h3>
                <p><?php echo $totalUsers; ?></p>
            </div>
            <div class="stat-box">
                <h3>Total Categories</h3>
                <p><?php echo $totalcategories; ?></p>
            </div>
        </div>
    </div>
</body>
</html>