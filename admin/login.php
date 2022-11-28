<?php
session_start();

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: index.php");
    exit;
}

require_once '../connection/connection.php';

$email = $password = "";
$email_err = $password_err = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if(empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email";
    } else {
        $email = trim($_POST["email"]);
    }
    

    if(empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password";
    } else {
        $password = $_POST["password"];
    }
    
    if(empty($email_err) && empty($password_err)) {
        $sql = "SELECT `id`, `email`, `password`, `user_type_id` FROM `user_account` WHERE `email` = ?";

        if( $stmt = mysqli_prepare($db, $sql)) {
            mysqli_stmt_bind_param($stmt, 's', $param_email);
            $param_email = $email;
            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password, $user_type_id);
                    if(mysqli_stmt_fetch($stmt)) {
                        if(md5($password) === $hashed_password) {
                            session_start();

                            $_SESSION['loggedin'] = true;
                            $_SESSION['id'] = $id;
                            $_SESSION['email'] = $email;
                            $_SESSION['user_type_id'] = $user_type_id;
                            
                            header("location: index.php");
                        } else {
                            $password_err = "The password you entered was not valid";
                        }
                    }
                } else {
                    $email_err = "No account found with that email.";
                }

            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($db);
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-5 col-md-5">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Admin Dashboard</h1>
                                    </div>
                                    <form class="user" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address...">
                                            <span class="text-danger"><?php echo $email_err; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                                            <span class="text-danger"><?php echo $password_err; ?></span>
                                        </div>
                                        <button type="submit" name="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                        <hr>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="boostrap/js/sb-admin-2.min.js"></script>

</body>
</html>
