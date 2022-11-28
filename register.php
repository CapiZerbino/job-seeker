<?php

include "connection/connection.php";

$email = $password = $confirm_password = $role = $user_type_id = "";
$registration_date = date('Y-m-d H:i:s');
$email_err = $password_err = $confirm_password_err = $role_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format";
    } else {
        $sql = "SELECT `id` FROM `user_account` WHERE `email` = ?";
        if ($stmt = mysqli_prepare($db, $sql)) {
            mysqli_stmt_bind_param($stmt, 's', $param_email);
            $param_email = trim($_POST["email"]);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "This useremail is already taken.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Oops! Something went wrong with validate email. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please enter a confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validate gender
    if (empty(trim($_POST["role"]))) {
        $role_err = "Please select your role";
    } else {
        $role = $_POST["role"];
        if ($role == "candidate") {
            $user_type_id = 4;
        } else {
            $user_type_id = 1;
        }
    }

    if (empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        # Check existing email
        $sql = "INSERT INTO `user_account` (`id`, `user_type_id`, `email`, `password`, `date_of_birth`, `gender`, `is_active`, `contact_number`, `sms_notification_active`, `email_notification_active`, `user_image`, `registration_date`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        if ($stmt = mysqli_prepare($db, $sql)) {
            mysqli_stmt_bind_param($stmt, 'iissssisiibs', $param_id, $param_user_type_id, $param_email, $param_password, $param_date_of_birth, $param_gender, $param_is_active, $param_contact_number, $param_sms_notification_active, $param_email_notification_active, $param_user_image, $param_registration_date);
            $param_id = null;
            $param_user_type_id = $user_type_id;
            $param_email = $email;
            $param_password = md5($password);
            $param_date_of_birth = null;
            $param_gender = null;
            $param_is_active = 1;
            $param_contact_number = null;
            $param_sms_notification_active = 1;
            $param_email_notification_active = 1;
            $param_user_image = null;
            $param_registration_date = $registration_date;
            if (mysqli_stmt_execute($stmt)) {
                header("location: login.php");
            } else {
                echo $date_of_birth;
                echo mysqli_stmt_error($stmt);
                echo "\nOops! Something went wrong. Please try again later.";
            }
            // Close statement
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
    <script src="https://kit.fontawesome.com/005d016a4d.js" crossorigin="anonymous"></script>
    <!-- Custom styles for this template-->
    <link href="admin/css/sb-admin-2.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <title>Register</title>
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
                                <h2 class="text-uppercase text-center mb-5">Create an account</h2>

                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="form3Example3cg">Your Email</label>
                                        <input type="email" id="form3Example3cg" name="email"
                                            class="form-control form-control-md" />
                                        <span class="text-danger">
                                            <?php echo $email_err; ?>
                                        </span>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="form3Example4cg">Password</label>
                                        <input type="password" id="form3Example4cg" name="password"
                                            class="form-control form-control-md" />
                                        <span class="text-danger">
                                            <?php echo $password_err; ?>
                                        </span>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="form3Example4cdg">Repeat your password</label>
                                        <input type="password" id="form3Example4cdg" name="confirm_password"
                                            class="form-control form-control-md" />
                                        <span class="text-danger">
                                            <?php echo $confirm_password_err; ?>
                                        </span>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="validatedCustomFile">Role</label>
                                        <select class="custom-select" name="role" required>
                                            <option value="candidate">Candidate</option>
                                            <option value="employee">Employee</option>
                                        </select>
                                        <span class="text-danger">
                                            <?php echo $role_err; ?>
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-center">
                                        <button type="submit" name="submit"
                                            class="btn btn-success btn-block btn-lg gradient-custom-4 text-body">Register</button>
                                    </div>

                                    <p class="text-center text-muted mt-5 mb-0">Have already an account? <a
                                            href="./login.php" class="fw-bold text-body"><u>Login here</u></a></p>
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