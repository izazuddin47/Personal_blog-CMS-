<?php
include 'db_conn.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle category addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $category_name = trim($_POST['category_name']);
    if (!empty($category_name)) {
        $category_name = mysqli_real_escape_string($conn, $category_name);
        $query = "INSERT INTO categories (name) VALUES ('$category_name')";
        mysqli_query($conn, $query);
        header("Location:categories.php");
        exit();
    }
}

// Fetch categories
$categories = mysqli_query($conn, "SELECT * FROM categories");

// Handle category deletion
if (isset($_GET['delete'])) {
    $category_id = intval($_GET['delete']);
    $query = "DELETE FROM categories WHERE id = $category_id";
    mysqli_query($conn, $query);
    header("Location: categories.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <meta name="description" content="Explore articles on [Category Name] such as [related topics].">
    <?php 
    $q = "select * from categories";
    $result = mysqli_query($conn, $q);
    if(mysqli_num_rows($result)){
        ?>
        <meta name="keywords" content="
        <?php
        while($row = mysqli_fetch_assoc($result)){
            echo $row['name'].", ";
        }
            ?>
            ">
            <?php
        }
    
?>
    <style>
        body {
            display: flex;
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f4f4f4;
        }
        .sidebar {
            width: 250px;
            background: #333;
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
        .main-content {
            flex-grow: 1;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        .form-group {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
        }
        button {
            padding: 10px 15px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            transition: 0.3s;
        }
        button:hover {
            background: #0056b3;
        }
        .categories-list {
            margin-top: 20px;
            border-radius: 10px;
            background: white;
            padding: 20px;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
        }
        .categories-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .categories-list li {
            padding: 12px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            font-size: 16px;
            color: #333;
        }
        .categories-list li:last-child {
            border-bottom: none;
        }
        .delete-btn {
            background: #dc3545;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: 0.3s;
        }
        .delete-btn:hover {
            background: #c82333;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Blog CMS</h2>
        <ul>
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="manage_post.php">Manage Post</a></li>
            <!-- <li><a href="categories.php" style="font-weight: bold;">Manage Categories</a></li> Active Page -->
            <li><a href="manage_comment.php">Manage Comments</a></li>
            <!-- <li><a href="user.php">Manage Users</a></li> -->
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h2>Manage Categories</h2>
            
            <!-- Add Category Form -->
            <form method="POST">
                <div class="form-group">
                    <input type="text" name="category_name" placeholder="Enter category name" required>
                    <button type="submit" name="add_category">Add</button>
                </div>
            </form>

            <!-- Categories List -->
            <div class="categories-list">
                <h3>Existing Categories</h3>
                <ul>
                    <?php while ($category = $categories->fetch_assoc()): ?>
                        <li>
                            <?= htmlspecialchars($category['name']); ?>
                            <a href="?delete=<?= $category['id']; ?>" class="delete-btn">Delete</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>

</body>
</html>