<?php
include 'db_conn.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = $_POST['post_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    // Insert comment into database
    $query = "INSERT INTO comments (post_id, name, email, comment, is_visible) 
              VALUES ('$post_id', '$name', '$email', '$comment', 1)";

    if (mysqli_query($conn, $query)) {
        // Redirect to the single post page with the post ID
        header("Location: single-post.php?id=" . $post_id . "&success=1");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>