<?php
session_start();

include("../../config/database/databaseConfig.php");

if (isset($_SESSION["user_id"]) && isset($_SESSION["login"])) {



    $userId = $_SESSION["user_id"];
    $loginStatus = $_SESSION["login"];

    $getuserInfoQuery = "SELECT * FROM user WHERE user_id='$userId'";

    $getuserInfoResult = mysqli_query($connection, $getuserInfoQuery);

    if (mysqli_num_rows($getuserInfoResult) <= 0) {
        echo "No user Found";
    } else {
        $row = mysqli_fetch_assoc($getuserInfoResult);
        $firstName = $row["user_first_name"] . "<br/>";
        $lastName = $row["user_last_name"] . "<br/>";
        $userEmail = $row["user_email"] . "<br/>";
        $userPhoneNumber = $row["user_phone_number"] . "<br/>";
        $userPhoto = $row["user_photo"] . "<br/>";
        $userPassword = $row["user_password"] . "<br/>";

       
    }
} else {
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
    exit(); 
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <section>
        <div>First Name: <?php echo $firstName?></div>
        <div>Last Name:  <?php echo $lastName?></div>
        <div>User Email: <?php echo $userEmail?></div>
        <div>Phone Number: <?php echo $userPhoneNumber?></div>
        <div>User Photo: <?php echo $userPhoto?></div>
        <div>User Password: <?php echo $userPassword?></div>
  
    </section>
    
</body>
</html>