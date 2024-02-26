<?php
session_start();

include("../../config/database/databaseConfig.php");

$userId = $_SESSION["user_id"];

echo $userId;
$userType = $_SESSION["user_type"];

$getAppliedJobHistoryQuery = "SELECT job_id, ja_status, client_user_id FROM job_application WHERE freelancer_user_id='$userId' and ja_status= 1";
$getAppliedJobHistoryResult = mysqli_query($connection, $getAppliedJobHistoryQuery);

if (mysqli_num_rows($getAppliedJobHistoryResult) > 0) {
    echo '<h1>My Jobs</h1>';
    while ($row = mysqli_fetch_assoc($getAppliedJobHistoryResult)) {
        $jobId = $row['job_id'];
        $clientUserId = $row["client_user_id"];

        // Retrieve job details from the "job" table
        $getJobDetailsQuery = "SELECT * FROM job WHERE job_id='$jobId' and job_status=1";
        $getJobDetailsResult = mysqli_query($connection, $getJobDetailsQuery);

        if ($getJobDetailsResult && mysqli_num_rows($getJobDetailsResult) > 0) {
            $jobDetails = mysqli_fetch_assoc($getJobDetailsResult);

            $jobId = $jobDetails["job_id"];

            // Output job details in card format with clickable link
            echo "<a href='http://localhost/freelancing-website/communication/chatbox.php?job_id=$jobId&toUser=$clientUserId' class='job-card'>";

            echo '<h2>' . $jobDetails['job_title'] . '</h2>';
            echo '<p>Description: ' . $jobDetails['job_description'] . '</p>';
            echo '</a>';

            $checkExistingRequestQuery = "SELECT * FROM job_close WHERE job_id=$jobId";
            $checkExistingRequestResult = mysqli_query($connection, $checkExistingRequestQuery);
            if(mysqli_num_rows($checkExistingRequestResult) > 0) {
                $row = mysqli_fetch_assoc($checkExistingRequestResult);
                $requesterId = $row["requester_id"];
                $responderId = $row["responder_id"];
                if($userId == $requesterId) {
                    echo "
                        <form action='http://localhost/freelancing-website/config/helper/deleteJobRequest.php?job_id=$jobId' method='POST'>
                            <input type='hidden' name='job_id' value='$jobId'>
                            <input type='hidden' name='freelancer_user_id' value=''>
                            <input class='close-job' type='submit' value='Delete Request'>
                        </form>";
                } else if($userId == $responderId) {
                    echo "
                        <form action='http://localhost/freelancing-website/config/helper/acceptJobClose.php?job_id=$jobId' method='POST'>
                            <input type='hidden' name='job_id' value='$jobId'>
                            <input class='close-job' type='submit' value='Accept Request'>
                        </form>";
                }
            } else {
                echo "
                    <form action='http://localhost/freelancing-website/config/helper/closeJob.php?job_id=$jobId&receiver_id=$clientUserId' method='POST'>
                        <input class='close-job' type='submit' value='Request Job Close'>
                    </form>";
            }
        } else {
       
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
    <style>
        /* CSS for Job Cards */
        .job-card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            display: block;
            text-decoration: none; /* Remove default link underline */
            color: inherit; /* Inherit text color */
        }

        .job-card:hover {
            background-color: #f0f0f0; /* Change background color on hover */
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
</head>
<body>
    
</body>
</html>
