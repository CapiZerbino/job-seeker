<?php 
    include('../connection/connection.php');
    $email = "nghia";
    $pass = "Nguyenkhuyen772";
    $date_of_birth = date('l jS \of F Y h:i:s A');
    $gender = "M";
    $is_active = true;
    $contact_number = "0933827830";
    $sms_notification_active = "";
    $user_image = "";
    $resistration_date = date('l jS \of F Y h:i:s A');

    $insertQuery = "INSERT INTO 
    user_account(email, pass, date_of_birth, gender, is_active, contact_number, sms_notification_active, user_image, resistration_date)
    VALUES('".$email.",".$pass.",".$date_of_birth.",".$gender.",".$is_active.",".$contact_number.",".$sms_notification_active.",".$user_image.",".$resistration_date." ')
    ";
    $result = $db->query($insertQuery);
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "Add success " .$row["email"];
        }
    } else {
        echo "Add faill";
    }
?>