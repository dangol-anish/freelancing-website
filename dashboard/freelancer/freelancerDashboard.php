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

<?php
session_start(); 

if( isset($_SESSION["user_id"]) && isset($_SESSION["login"])) {

    echo "User Id: " . $_SESSION["user_id"]. "<br/>" ;
    echo "Session Status: " . $_SESSION["login"];
}else{
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
}
?>
