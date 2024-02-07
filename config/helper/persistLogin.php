<?php

session_start(); // Start the session

// Check if the user is already logged in
if(isset($_SESSION["user_id"]) && isset($_SESSION["login"])) {
    header("Location: http://localhost/freelancing-website/dashboard/dashboard.php");
    exit; // Make sure no code is executed after redirection
}

?>