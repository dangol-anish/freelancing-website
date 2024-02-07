<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    Dashboard

    <a href="http://localhost/freelancing-website/dashboard/logout.php">Logout</a>
</body>
</html>

<?php
session_start(); // Start the session if not already started

if( isset($_SESSION["user_id"]) && isset($_SESSION["login"])) {

    echo $_SESSION["user_id"];
    echo $_SESSION["login"];
}else{
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
}
?>
