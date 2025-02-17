<?php


include 'db_conn.php'; // Ensure this file connects to the database properly

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn , $_POST['role']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
  

   

    $errors = [];

    // Validation checks
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password should be at least 6 characters.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "The email is invalid.";
    }

    // Check if email already exists
    $email_check_query = "SELECT email FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $email_check_query);
    if (mysqli_num_rows($result) > 0) {
        $errors[] = "Email already exists.";
    }

    $checkadmin = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'admin'");
    $result = mysqli_fetch_assoc($checkadmin);
    $role = ($result['total'] == 0) ? 'admin' : 'user';
    

    // Insert data if no errors
    if (empty($errors)) {
        $hashed_password = password_hash($_POST['password'],PASSWORD_DEFAULT); // Secure password hashing
        $query = "INSERT INTO users (name, email, password , role) VALUES ('$name', '$email', '$hashed_password' , '$role')";

        if (mysqli_query($conn, $query)) {
            echo "<div class='alert alert-success'>Registration successful! You can now log in.</div>";
            header("Location: login.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
        }
    } else {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fa;
            font-family: Arial, sans-serif;
        }
        .card {
            border-radius: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            padding: 3rem;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #0066cc;
            border-color: #0066cc;
        }
        .btn-primary:hover {
            background-color: #005bb5;
            border-color: #005bb5;
        }
        .alert {
            margin-top: 15px;
        }
        .d-flex {
            display: flex;
            justify-content: center;
        }
        .btn-lg {
            padding: 0.5rem 2rem;
            font-size: 1.25rem;
        }
        .form-control {
            border-radius: 10px;
        }
    </style>
</head>
<body>

<section class="vh-100" style="background-color: #f4f7fa;">
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-12 col-xl-11">
                <div class="card text-black" style="border-radius: 25px;">
                    <div class="card-body p-md-5">
                        <div class="row justify-content-center">
                            <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign Up</p>
                                <form class="mx-1 mx-md-4" action="" method="POST">

                                    <div class="mb-4">
                                        <label class="form-label" for="name">Your Name</label>
                                        <input type="text" name="name" id="name" class="form-control" required />
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label" for="email">Your Email</label>
                                        <input type="email" name="email" id="email" class="form-control" required />
                                    </div>


                                    <div class="mb-4">
                                        <label class="form-label" for="password">Password</label>
                                        <input type="password" name="password" id="password" class="form-control" required />
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label" for="confirm_password">Confirm Password</label>
                                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required />
                                    </div>

                                    <select name="role" id="role" class="form-control">
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                            </select>

                                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                        <button type="submit" name="register" class="btn btn-primary btn-lg">Register</button>
                                    </div>

                                    

                                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                        <a href="login.php" class="btn btn-secondary btn-lg">Already have an account? Login</a>
                                    </div>


                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</body>
</html>
