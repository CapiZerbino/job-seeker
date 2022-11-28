<?php
    include("../connection/connection.php");
    $getQuery = "SELECT * FROM `user_type`";
    $result = $db->query($getQuery);
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "Get successful" .$row["user_type_name"];
        }
        
    } else {
        echo "Get fail";
    }
?>