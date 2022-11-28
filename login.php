<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: index.php");
    exit;
}

require_once 'connection/connection.php';

$email = $password = "";
$email_err = $password_err = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email";
    } else {
        $email = trim($_POST["email"]);
    }


    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password";
    } else {
        $password = $_POST["password"];
    }

    if (empty($email_err) && empty($password_err)) {
        $sql = "SELECT `id`, `email`, `password`, `user_type_id` FROM `user_account` WHERE `email` = ?";

        if ($stmt = mysqli_prepare($db, $sql)) {
            mysqli_stmt_bind_param($stmt, 's', $param_email);
            $param_email = $email;
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password, $user_type_id);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (md5($password) === $hashed_password) {
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
    <title>Document</title>
</head>

<body>
    <?php include "layouts/navigation.php" ?>


    <section class="vh-100 bg-white">
        <div class="mask d-flex align-items-center h-100 gradient-custom-3">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                        <div class="card" style="border-radius: 15px;">
                            <div class="card-body p-5">
                                <h2 class="text-uppercase text-center mb-5">Login an account</h2>

                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="form3Example3cg">Email</label>
                                        <input type="email" name="email" id="form3Example3cg"
                                            class="form-control form-control-md" />
                                        <span class="text-danger">
                                            <?php echo $email_err; ?>
                                        </span>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="form3Example4cg">Password</label>
                                        <input type="password" name="password" id="form3Example4cg"
                                            class="form-control form-control-md" />
                                        <span class="text-danger">
                                            <?php echo $password_err; ?>
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-center">
                                        <button type="submit" name="submit"
                                            class="btn btn-success btn-block btn-lg gradient-custom-4 text-body">Login</button>
                                    </div>

                                    <p class="text-center text-muted mt-5 mb-0">Do not have an account? <a
                                            href="./register.php" class="fw-bold text-body"><u>Register
                                                here</u></a></p>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include "layouts/footer.php" ?>
</body>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

</html>