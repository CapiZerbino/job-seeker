<?php include 'layouts/session.php' ?>
<?php
include "../connection/connection.php";

if (!isset($_SESSION["email"]) || $_SESSION["user_type_name"] != 'admin') {
    header('location:index.php');
}

$email = $date_of_birth = $gender = $contact_number = "";
$password = md5("staff@1234");
$registration_date = date('Y-m-d H:i:s');
$email_err = $date_of_birth_err = $gender_err = $contact_number_err = "";

if($_SERVER['REQUEST_METHOD'] == "POST") {
    // Validate email
    if(empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format";
    } else {
        $sql = "SELECT `id` FROM `user_account` WHERE `email` = ?";
        if($stmt = mysqli_prepare($db, $sql)) {
            mysqli_stmt_bind_param($stmt, 's', $param_email);
            $param_email = trim($_POST["email"]);
            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1) {
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

    // Validate date of birth
    if(empty(trim($_POST["date_of_birth"]))) {
        $date_of_birth_err = "Please enter your date of birth";
    } else {
        $date_of_birth = $_POST["date_of_birth"];
    }
    // Validate gender
    if(empty(trim($_POST["gender"]))) {
        $gender_err = "Please enter your gender";
    } else {
        $gender = $_POST["gender"];
    }
    // Validate contact number
    if(empty(trim($_POST["contact_number"]))) {
        $contact_number_err = "Please enter your phone number";
    } else {
        $contact_number = $_POST["contact_number"];
    }
    if(empty($email_err) && empty($date_of_birth_err) && empty($gender_err) && empty($contact_number_err)) {
        # Check existing email
       $sql = "INSERT INTO `user_account` (`id`, `user_type_id`, `email`, `password`, `date_of_birth`, `gender`, `is_active`, `contact_number`, `sms_notification_active`, `email_notification_active`, `user_image`, `registration_date`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
       if($stmt = mysqli_prepare($db, $sql)) {
            mysqli_stmt_bind_param($stmt, 'iissssisiibs', $param_id, $param_user_type_id, $param_email, $param_password, $param_date_of_birth, $param_gender, $param_is_active, $param_contact_number, $param_sms_notification_active, $param_email_notification_active, $param_user_image, $param_registration_date);
            $param_id= null;
            $param_user_type_id = 3;
            $param_email = $email; 
            $param_password = $password; 
            $param_date_of_birth = $date_of_birth; 
            $param_gender = $gender; 
            $param_is_active = 1; 
            $param_contact_number = $contact_number; 
            $param_sms_notification_active = 1; 
            $param_email_notification_active = 1; 
            $param_user_image = null; 
            $param_registration_date = $registration_date;
            if(mysqli_stmt_execute($stmt)) {
                header("location: all_staff.php");
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
    <?php include "layouts/head.php" ?>
    <title>Admin Panel</title>
</head>

<body>
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include 'layouts/sidebar.php'?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <?php include 'layouts/topbar.php'?>
                
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Add staff</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <form class="needs-validation" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="validationCustomUsername">Email</label>
                                    <div class="input-group has-error">
                                        <input type="email" name="email" class="form-control" id="validationCustomUsername"
                                            placeholder="Email" aria-describedby="inputGroupPrepend" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroupPrepend">@</span>
                                        </div>
                                        <span class="text-danger"><?php echo $email_err; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-3 mb-3">
                                    <label for="validationCustom03">Date of birth</label>
                                    <div class="input-group date" id="datepicker">
                                        <input type="date" name="date_of_birth" class="form-control" id="date" />
                                        <span class="input-group-append">
                                            <span class="input-group-text bg-light d-block">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                        </span>
                                    </div>
                                    <span class="text-danger"><?php echo $date_of_birth_err; ?></span>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="validationCustom04">Contact number</label>
                                    <input type="number" name="contact_number" class="form-control" id="validationCustom04"
                                        placeholder="0933827830" required>
                                    <span class="text-danger"><?php echo $contact_number_err; ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="validatedCustomFile">Gender</label>
                                <select class="custom-select" name="gender" required>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                </select>
                                <span class="text-danger"><?php echo $gender_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label for="validatedCustomFile">Profile Image</label>
                                <div class="custom-file">
                                    <input type="file" name="file" class="custom-file-input" id="validatedCustomFile">
                                    <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit" name='submit'>Add</button>
                        </form>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->
    </div>
</body>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

</html>