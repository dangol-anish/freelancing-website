<?php
session_start(); // Start the session if not already started

if( isset($_SESSION["user_id"]) && isset($_SESSION["login"])) {

    if($_SESSION["user_type"] != "Admin"){
    $_SESSION = [];
    session_destroy();
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");

    }
    
}else{
     $_SESSION = [];
    session_destroy();
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");

}


include("../../config/database/databaseConfig.php");


$getClosedJobsQuery = "select * from job where job_status = 0";
$getClosedJobsResult = mysqli_query($connection, $getClosedJobsQuery);

if(mysqli_num_rows($getClosedJobsResult) > 0){

while ($row = mysqli_fetch_assoc($getClosedJobsResult)) {
    $jobId = $row["job_id"];
    echo "<a href='http://localhost/freelancing-website/dashboard/admin/verifyJobDetails.php?job_id=$jobId'>" . $row["job_title"] ."</a>";


}

}else{
    echo "No jobs found";
}