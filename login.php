<?php
include 'db_conn.php';

if(isset($_POST['submit'])){
   $email = mysqli_real_escape_string($conn , $_POST['email']);
   $password = mysqli_real_escape_string($conn , $_POST['password']);

   $sql = "SELECT * from users where email = '$email'";
   $result = mysqli_query($conn , $sql) or die("query failed" . mysqli_error($conn));

   if(mysqli_num_rows($result) > 0){
    $user = mysqli_fetch_assoc($result);
//     echo "<pre>";
//          print_r($user);
//    echo "</pre>"
    if(password_verify($password , $user['password'])){
        // echo "login successful";
           $_SESSION['name'] = $user['name'];
           $_SESSION['username']  = $user['email'];
           $_SESSION['logged_in'] = true;
           $_SESSION['user_id'] = $user['id'];
           
           if ($user['role'] == 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: user.php");
        }
        exit();
    }else{
        $error_message = "inviled password";
    }
}else{
        $error_message = "email not found";
    }
   }




   






?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
                                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign In</p>

                                <?php
                                if (!empty($error_message)) {
                                    echo "<div class='alert alert-danger'>$error_message</div>";
                                }
                                ?>

                                <form class="mx-1 mx-md-4" action="" method="POST">
                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input type="text" name="email" class="form-control" placeholder="Enter your email" required />
                                            <label class="form-label">Email</label>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input type="password" name="password" class="form-control" placeholder="Enter your password" required />
                                            <label class="form-label">Password</label>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                        <button type="submit" name="submit" class="btn btn-primary btn-lg">Login</button>
                                    </div>
                                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                        <a href="register.php" class="btn btn-secondary btn-lg">Don't have an account? Register</a>
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