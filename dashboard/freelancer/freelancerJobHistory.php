<?php
session_start();

include("../../config/database/databaseConfig.php");

$userId = $_SESSION["user_id"];

$getAppliedJobHistoryQuery = "SELECT job_id, ja_status, client_user_id FROM job_application WHERE freelancer_user_id='$userId'";
$getAppliedJobHistoryResult = mysqli_query($connection, $getAppliedJobHistoryQuery);

if (mysqli_num_rows($getAppliedJobHistoryResult) > 0) {
    while ($row = mysqli_fetch_assoc($getAppliedJobHistoryResult)) {
        $jobId = $row['job_id'];
        $jobApplicationStatus = $row['ja_status'];
        $toUser = $row["client_user_id"];

        if($jobApplicationStatus == 0){
            $jobApplication = "In Review";
        } elseif ($jobApplicationStatus == 1){
            $jobApplication = "Accepted";
        } elseif ($jobApplicationStatus == 2){
            $jobApplication = "Rejected";
        } else {
            $jobApplication = "Error";
        }

        // Retrieve job details from the "job" table
        $getJobDetailsQuery = "SELECT * FROM job WHERE job_id='$jobId'";
        $getJobDetailsResult = mysqli_query($connection, $getJobDetailsQuery);

        if ($getJobDetailsResult) {
            $jobDetails = mysqli_fetch_assoc($getJobDetailsResult);

            // Output job details in card format
          echo "<a href='http://localhost/freelancing-website/communication/chatbox.php?job_id=$jobId&toUser=$toUser' class='job-card'>";

            echo '<h2>' . $jobDetails['job_title'] . '</h2>';
            echo '<p>Description: ' . $jobDetails['job_description'] . '</p>';
            echo $jobApplication;
            echo '</a>';
        } else {
            echo "Error fetching job details";
        }
    }
} else {
    echo "No Jobs Found";
}
?>
