<?php
session_start();
include("../../config/database/databaseConfig.php");

if(isset($_POST['job_id'])) {
    $jobId = $_POST['job_id'];
    $userType = $_SESSION["user_type"];

    $userId = $_SESSION['user_id'];

    // Perform the deletion of the request
    $deleteRequestQuery = "DELETE FROM job_close WHERE job_id=$jobId AND (requester_id=$userId OR responder_id=$userId)";
    $deleteRequestResult = mysqli_query($connection, $deleteRequestQuery);

    if($deleteRequestResult) {

            if($userType == "Client"){
        header("location: http://localhost/freelancing-website/dashboard/client/activeJob.php");
    }else if( $userType == "Freelancer"){
        header("location: http://localhost/freelancing-website/dashboard/freelancer/myJobs.php");
    }

        exit();
    } else {
        // Handle deletion failure
        echo "Error: Unable to delete request.";
    }
} else {
    // Handle case where job_id or freelancer_user_id is not set
    echo "Error: Missing parameters.";
}
?>
