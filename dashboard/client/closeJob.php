<?php
session_start(); // Start the session if not already started

include("../../config/database/databaseConfig.php");

if(isset($_SESSION["user_id"]) && isset($_SESSION["login"])) {
    if($_SESSION["user_type"] != "Client") {
        $_SESSION = [];
        session_destroy();
        header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
        exit;
    }

    $requesterId = $_SESSION["user_id"];
    $responderId = $_GET["receiver_id"];
    $jobId = $_GET["job_id"];

    // Check if the request already exists
    $checkExistingRequestQuery = "SELECT * FROM job_close WHERE job_id = $jobId";
    $checkExistingRequestResult = mysqli_query($connection, $checkExistingRequestQuery);

    if(mysqli_num_rows($checkExistingRequestResult) > 0) {
    
        exit;
    }

    // Insert the new request
    $closeJobRequestQuery = "INSERT INTO job_close (requester_id, responder_id, job_id) VALUES('$requesterId', '$responderId', '$jobId')";
    $closeJobRequestResult = mysqli_query($connection, $closeJobRequestQuery);

    if($closeJobRequestResult){
    header("location: http://localhost/freelancing-website/dashboard/client/activeJob.php");
    } else {
        echo "error: " . mysqli_error($connection); // Output the error message for debugging purposes
    }
} else {
    $_SESSION = [];
    session_destroy();
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
    exit;
}
?>
