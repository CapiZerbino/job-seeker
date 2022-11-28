<?php
include "layouts/session.php";
include "connection/connection.php";
include "layouts/utils.php";
$company_name = $company_description = $company_image = $company_type = $company_url = $company_establish_date = $job_skill = $job_skill_level = $job_type = $job_description = "";
$company_name_err = $company_description_err = $company_image_err = $company_type_err = $company_url_err = $company_establish_date_err = $job_skill_err = $job_skill_level_err = $job_type_err = $job_description_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Validate company name
    if (empty(trim($_POST["company_name"]))) {
        $company_name_err = "Please enter your company name";
    } else {
        $company_name = $_POST["company_name"];
    }

    // Validate company description
    if (empty(trim($_POST["company_description"]))) {
        $company_description_err = "Please enter your company name";
    } else {
        $company_name = $_POST["company_description"];
    }

    // Validate image upload
    // if (isset($_POST["company_image"])) {
    //     $allowed_image_extension = array(
    //         "png",
    //         "jpg",
    //         "jpeg"
    //     );

    //     // Get image file extension
    //     $file_extension = pathinfo($_FILES["company_image"]["name"], PATHINFO_EXTENSION);
    //     // Validate file input is not empty
    //     // function_alert($file_extension);
    //     if (!file_exists($_FILES["company_image"]["tmp_name"])) {
    //         $response = array(
    //             "type" => "error",
    //             "message" => "Choose image file to upload."
    //         );
    //     }
    //     // Validate file input is correct extension
    //     elseif (!in_array($file_extension, $allowed_image_extension)) {
    //         $respon = array(
    //             "type" => "error",
    //             "message" => "Upload valid images. Only PNG and JPEG are allowed."
    //         );
    //     }
    //     // Validate image file size
    //     elseif ($_FILES["company_image"]["size"] > 2000000) {
    //         $response = array(
    //             "type" => "error",
    //             "message" => "Image size exceeds 2MB"
    //         );
    //     } else {
    //         $target = "image/" . basename($_FILES["company_image"]["name"]);
    //         if (move_uploaded_file($_FILES["company_image"]["tmp_name"], $target)) {
    //             $response = array(
    //                 "type" => "success",
    //                 "message" => "Image uploaded successfully."
    //             );
    //         } else {
    //             $response = array(
    //                 "type" => "error",
    //                 "message" => "Problem in uploading image files."
    //             );
    //         }
    //     }
    // }

    if (empty(trim($_POST["company_type"]))) {
        $company_type_err = "Please enter your company name";
    } else {
        $company_type = $_POST["company_description"];
    }

    if (empty(trim($_POST["company_establish_date"]))) {
        $company_establish_date_err = "Please enter your company establish date";
    } else {
        $company_establish_date = $_POST["company_establish_date"];
    }

    if (empty(trim($_POST["job_skill"]))) {
        $job_skill_err = "Please select your company job skill";
    } else {
        $job_skill = $_POST["job_skill"];
    }

    if (empty(trim($_POST["job_skill_level"]))) {
        $job_skill_level_err = "Please select your job skill level";
    } else {
        $job_skill_level = $_POST["job_skill_level"];
    }

    if (empty(trim($_POST["job_type"]))) {
        $job_type_err = "Please select your company job type";
    } else {
        $job_type = $_POST["job_type"];
    }

    if (empty(trim($_POST["job_description"]))) {
        $job_description_err = "Please enter your job description";
    } else {
        $job_description = $_POST["job_description"];
    }

    if (empty($company_name_err) && empty($company_description_err) && empty($company_image_err) && empty($company_type_err) && empty($company_url_err) && empty($company_establish_date_err) && empty($job_skill_err) && empty($job_skill_level_err) && empty($job_type_err) && empty($job_description_err)) {
        $sql_company = "INSERT INTO `company` (`id`, `company_name`, `profile_description`, `business_stream_id`, `establishment_date`, `company_website_url`) VALUES (?,?,?,?,?,?');";
        if($stmt = mysqli_prepare($db, $sql_company)) {
            mysqli_stmt_bind_param($stmt, 'ississ', $param_id, $param_company_name, $param_company_description, $param_bussiness_stream_id, $param_establishment_date, $param_company_website_url);
            $param_id = null;
            $param_company_name = $company_name;
            $param_company_description = $company_description;
            $param_bussiness_stream_id = $company_type;
            $param_establishment_date = $company_establish_date;
            $param_company_website_url = $company_url;
            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                // $sql_company_image = "INSERT INTO `company_image` (`id`, `company_id`, `company_image`) VALUES (?,?,?)";
                // if($stmt2 = mysqli_prepare($db, $sql_company_image)) {
                //     mysqli_stmt_bind_param($stmt2, 'iis', $param_image_id, $param_company_id, )
                // }

            }
        }

    }
}


?>
<html lang="en">

<head>
    <?php include "layouts/head.php" ?>
    <title>Create a CV</title>
</head>

<body>
    <?php include "layouts/navigation.php" ?>

    <section class="vh-100 bg-white">
        <div class="mask d-flex align-items-center gradient-custom-3">
            <div class="container p-5">
                <div class="row d-flex justify-content-center align-items-center">
                    <div class="col-12 col-md-9 col-lg-7 col-xl-6">

                        <h2 class="text-uppercase text-center mb-5">Create a job description</h2>

                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                            <!-- Company name -->
                            <div class="form-outline mb-4">
                                <label class="form-label">Company Name</label>
                                <input type="text" name="company_name" class="form-control form-control-md" />
                                <span class="text-danger">
                                    <?php echo $company_name_err; ?>
                                </span>
                            </div>
                            <!-- Company desciption -->
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Company Description</label>
                                <textarea class="form-control" name="company_description" id="exampleFormControlTextarea1" rows="3"></textarea>
                                <span class="text-danger">
                                    <?php echo $company_description_err; ?>
                                </span>
                            </div>
                            <!-- Company image -->
                            <div class="form-group">
                                <label for="validatedCustomFile">Company Logo</label>
                                <div class="custom-file">
                                    <input type="file" name="company_image" class="custom-file-input" id="validatedCustomFile">
                                    <label class="custom-file-label" for="validatedCustomFile">Choose
                                        file...</label>
                                </div>
                                <?php if (!empty($response)) { ?>
                                    <div class="response <?php echo $response["type"]; ?>">
                                        <?php echo $response["message"]; ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <!-- Job Type -->
                            <div class="form-group">
                                <label for="validatedCustomFile">Company Type</label>
                                <select class="custom-select" name="company_type" required>
                                <?php
                                    $sql = "SELECT * FROM `business_stream`";
                                    if ($stmt = mysqli_prepare($db, $sql)) {
                                        if (mysqli_stmt_execute($stmt)) {
                                            mysqli_stmt_store_result($stmt);
                                            $num_row = mysqli_stmt_num_rows($stmt);
                                            while ($num_row >= 1) {
                                                mysqli_stmt_bind_result($stmt, $business_stream_id, $business_stream_name);
                                                if (mysqli_stmt_fetch($stmt)) {
                                                    echo "<option value='$business_stream_id'>$business_stream_name</option>";
                                                }
                                                $num_row = $num_row - 1;
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                                <span class="text-danger">
                                    <?php echo $job_type_err; ?>
                                </span>
                            </div>
                            <!-- Company url -->
                            <div class="form-outline mb-4">
                                <label class="form-label">Company URL</label>
                                <input type="text" name="company_url" class="form-control" />
                                <span class="text-danger">
                                    <?php echo $company_url_err; ?>
                                </span>
                            </div>
                            <!-- Company establishment date -->
                            <div class="form-outline mb-4">
                                <label for="validationCustom03">Company Establishment Date</label>
                                <div class="input-group date" id="datepicker">
                                    <input type="date" name="company_establish_date" class="form-control form-control-md" id="date" />
                                    <span class="input-group-append">
                                        <span class="input-group-text bg-light d-block">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </span>
                                </div>
                                <span class="text-danger">
                                    <?php echo $company_establish_date_err; ?>
                                </span>
                            </div>
                            <!-- Job Skill -->

                            <div class="form-group">
                                <label for="validatedCustomFile">Job Skill</label>
                                <select class="custom-select" name="job_skill" multiple required>
                                    <?php
                                    $sql = "SELECT * FROM `skill_set`";
                                    if ($stmt = mysqli_prepare($db, $sql)) {
                                        if (mysqli_stmt_execute($stmt)) {
                                            mysqli_stmt_store_result($stmt);
                                            $num_row = mysqli_stmt_num_rows($stmt);
                                            while ($num_row >= 1) {
                                                mysqli_stmt_bind_result($stmt, $skill_id, $skill_name);
                                                if (mysqli_stmt_fetch($stmt)) {
                                                    echo "<option value='$skill_id'>$skill_name</option>";
                                                }
                                                $num_row = $num_row - 1;
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                                <span class="text-danger">
                                    <?php echo $job_skill_err; ?>
                                </span>
                            </div>
                            <!-- Job Skill Level -->
                            <div class="form-group">
                                <label for="validatedCustomFile">Job Skill Level</label>
                                <select class="custom-select" name="job_skill_level" required>
                                    <option value="fresher">Fresher</option>
                                    <option value="junior">Junior</option>
                                    <option value="senior">Senior</option>
                                    <option value="professional">Professional</option>
                                </select>
                                <span class="text-danger">
                                    <?php echo $job_skill_err; ?>
                                </span>
                            </div>
                            <!-- Job Type -->
                            <div class="form-group">
                                <label for="validatedCustomFile">Job Type</label>
                                <select class="custom-select" name="job_type" required>
                                <?php
                                    $sql = "SELECT * FROM `job_type`";
                                    if ($stmt = mysqli_prepare($db, $sql)) {
                                        if (mysqli_stmt_execute($stmt)) {
                                            mysqli_stmt_store_result($stmt);
                                            $num_row = mysqli_stmt_num_rows($stmt);
                                            while ($num_row >= 1) {
                                                mysqli_stmt_bind_result($stmt, $job_type_id, $job_type_name);
                                                if (mysqli_stmt_fetch($stmt)) {
                                                    echo "<option value='$job_type_id'>$job_type_name</option>";
                                                    echo $skill_name;
                                                }
                                                $num_row = $num_row - 1;
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                                <span class="text-danger">
                                    <?php echo $job_type_err; ?>
                                </span>
                            </div>
                            <!-- Job location -->
                            <!-- Job description -->
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Job Description</label>
                                <textarea class="form-control" name="job_description" id="exampleFormControlTextarea1" rows="3"></textarea>
                                <span class="text-danger">
                                    <?php echo $job_description_err; ?>
                                </span>
                            </div>
                            <!-- Status -->

                            <div class="d-flex justify-content-center">
                                <button type="submit" name="submit" class="btn btn-success btn-block btn-lg gradient-custom-4 text-body">Submit</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include "layouts/footer.php" ?>
</body>

</html>