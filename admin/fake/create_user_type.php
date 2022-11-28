<?php 
    include("../connection/connection.php");
    $employee = "employee";
    $candidate = "candidate";

    $insert_query = "INSERT INTO user_type(user_type_name) VALUES('".$employee."')";
    $result = $db->query($db, $insert_query);
    if($result) {
        echo "Insert success";
    } else {
        echo "Insert fail";
    }
?>