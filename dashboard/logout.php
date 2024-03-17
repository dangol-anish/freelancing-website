<?php
session_start(); // Start the session

require("../config/database/databaseConfig.php");

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
exit; // Make sure no code is executed after redirection
?>
