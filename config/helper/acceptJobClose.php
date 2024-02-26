<?php
session_start();
include("../../config/database/databaseConfig.php");

if(isset($_POST['job_id'])) {
    $jobId = $_POST['job_id'];
    $userType = $_SESSION["user_type"];


    $closeJobQuery = "update job set job_status=2 where job_id='$jobId'";
    $closeJobResult = mysqli_query($connection, $closeJobQuery);

    // After mysqli_query
if(!$closeJobResult) {
    echo "Error: " . mysqli_error($connection);
    // Log the error for further investigation
}


    if($closeJobResult) {
       

            if($userType == "Client"){
        header("location: http://localhost/freelancing-website/dashboard/client/activeJob.php");
    }else if( $userType == "Freelancer"){
        header("location: http://localhost/freelancing-website/dashboard/freelancer/myJobs.php");
    }

        exit();
    }else {
        echo "Errr";
    }

}
?>