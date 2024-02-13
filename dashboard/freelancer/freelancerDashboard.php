<?php
session_start(); // Start the session if not already started



if( isset($_SESSION["user_id"]) && isset($_SESSION["login"])) {

    if($_SESSION["user_type"] != "Freelancer"){
    $_SESSION = [];
    session_destroy();
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");

    }
    
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
    Dashboard
    <br>
    <a href="http://localhost/freelancing-website/dashboard/logout.php">Logout</a>
    <br>
    <a href="http://localhost/freelancing-website/dashboard/freelancer/freelancerProfile.php">My Profile</a>
    <br>
</body>
</html>

