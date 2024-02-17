<?php
session_start();

include("../../config/database/databaseConfig.php");

$userId = $_SESSION["user_id"];

$getAppliedJobHistoryQuery = "SELECT job_id, ja_status FROM job_application WHERE freelancer_user_id='$userId'";
$getAppliedJobHistoryResult = mysqli_query($connection, $getAppliedJobHistoryQuery);



if (mysqli_num_rows($getAppliedJobHistoryResult) > 0) {
    while ($row = mysqli_fetch_assoc($getAppliedJobHistoryResult)) {
        $jobId = $row['job_id'];

        $jobApplicationStatus = $row['ja_status'];


        if($jobApplicationStatus == 0){
            $jobApplication = "In Review";
        }elseif ($jobApplicationStatus == 1){
     $jobApplication = "Accepted";
        }else if( $jobApplicationStatus == 2){
              $jobApplication = "Rejected";
        }
        else{
            echo "error";
        }

        // Retrieve job details from the "job" table
        $getJobDetailsQuery = "SELECT * FROM job WHERE job_id='$jobId'";
        $getJobDetailsResult = mysqli_query($connection, $getJobDetailsQuery);

        if ($getJobDetailsResult) {
            $jobDetails = mysqli_fetch_assoc($getJobDetailsResult);

            // Output job details in card format
            echo '<div class="job-card">';
            echo '<h2>' . $jobDetails['job_title'] . '</h2>';
            echo '<p>Description: ' . $jobDetails['job_description'] . '</p>';
            echo $jobApplication;
            echo '</div>';
        } else {
            echo "Error fetching job details";
        }
    }
} else {
    echo "No Jobs Found";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>

<style>
    /* CSS for Job Cards */
.job-card {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 10px;
}

.job-card h2 {
    margin-top: 0;
    margin-bottom: 5px;
}

.job-card p {
    margin-top: 0;
    margin-bottom: 0;
}

</style>
</html>