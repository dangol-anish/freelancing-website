<?php
session_start(); // Start the session

// Check if the user is already logged in
if(isset($_SESSION["user_id"]) && isset($_SESSION["login"])) {
    
    // Check the value of $_SESSION["user_type"]
    if($_SESSION["user_type"] == "Client") {
        header("Location: http://localhost/freelancing-website/dashboard/client/clientDashboard.php");
    } else if($_SESSION["user_type"] == "Freelancer") {
        header("Location: http://localhost/freelancing-website/dashboard/freelancer/freelancerDashboard.php");
    } else if($_SESSION["user_type"] == "Admin") {
        header("Location: http://localhost/freelancing-website/dashboard/admin/adminDashboard.php");
    } else {
        exit;
    }
}
?>
