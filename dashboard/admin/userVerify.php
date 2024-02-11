<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php
session_start(); 

if( isset($_GET["user_verification_id"]) && isset($_SESSION["login"])) {

    echo "User Id: " . $_GET["user_verification_id"]. "<br/>" ;
    echo "Session Status: " . $_SESSION["login"];
}else{
    echo "Error";
}
?>

    
</body>
</html>