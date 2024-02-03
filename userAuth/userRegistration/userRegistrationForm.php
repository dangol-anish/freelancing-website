<?php
// Include the database configuration file
require("../../config/database/databaseConfig.php");

// Check if form is submitted
if(isset($_POST['Submit'])){
    // Retrieve form data
    $user_first_name = $_POST['user_first_name'];
    $user_last_name = $_POST['user_last_name'];
    $user_password = $_POST['user_password'];
    $user_email = $_POST['user_email'];
    $user_phone_number = $_POST['user_phone_number'];
    $user_type = $_POST['user_type'];

    // File upload
    $file_name = $_FILES['user_photo']['name'];
    $temp_name = $_FILES['user_photo']['tmp_name'];
    $folder="pics/".$file_name;
    if (move_uploaded_file($temp_name, $folder)) {
        echo "File uploaded successfully.";
    } else {
        echo "Error moving file.";
    }

    // Insert data into database
    $sql = "INSERT INTO user (user_first_name, user_last_name, user_password, user_email, user_phone_number, user_type, user_photo)
            VALUES ('$user_first_name', '$user_last_name', '$user_password', '$user_email', '$user_phone_number', '$user_type', '$folder')";
    
    $result=mysqli_query($connection,$sql);
    
    // Check if insertion was successful
    if($result){
        echo "Records inserted successfully";
    }else{
        echo "Records not inserted";
    }
}
?>
