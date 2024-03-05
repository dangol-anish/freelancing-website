<?php
session_start();

include("../../config/database/databaseConfig.php");

if(isset($_SESSION["user_id"]) && isset($_SESSION["login"])) {
    if($_SESSION["user_type"] != "Client") {
        $_SESSION = [];
        session_destroy();
        header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
        exit;
    }

    $userId = $_SESSION["user_id"];

    $getClientStatusQuery = "SELECT user_status FROM user WHERE user_id='$userId'";
    $getClientStatusResult = mysqli_query($connection, $getClientStatusQuery);

    if(mysqli_num_rows($getClientStatusResult) > 0) {
        $row = mysqli_fetch_assoc($getClientStatusResult);
        $userStatus = $row['user_status'];
        $disableCreateJobButton = ($userStatus == 2 || $userStatus == 0);
    }
}

$userType = $_SESSION["user_type"];

// Fetch jobs associated with the current user where ja_status is 1
$getJobDataQuery = "
    SELECT j.*, ja.*, CONCAT(u.user_first_name, ' ', u.user_last_name) AS freelancer_name, ja.job_id AS job_id, ja.freelancer_user_id AS freelancer_user_id
    FROM job j
    INNER JOIN job_application ja ON j.job_id = ja.job_id
    INNER JOIN user u ON ja.freelancer_user_id = u.user_id
    WHERE j.user_id='$userId' AND ja.ja_status = 1 and j.job_status=1";
$getJobDataResult = mysqli_query($connection, $getJobDataQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <link rel="stylesheet" href="clientDashboard.css">
</head>
<body>
<?php include("clientHeader.html") ?>
<main>
<?php include("createJob.php");?>
<h2 style="padding-bottom:20px">Active Jobs</h2>
<?php
if(mysqli_num_rows($getJobDataResult) > 0){
    while($jobInfo = mysqli_fetch_assoc($getJobDataResult)) {
        $applicantUserId = $jobInfo["freelancer_user_id"];
        $jobId = $jobInfo["job_id"];
?>
        <div class="job-card">
            <a href='http://localhost/freelancing-website/communication/chatbox.php?job_id=$jobId&toUser=<?php echo $applicantUserId?>&user_type=<?php echo $userType?>&client_user_id=<?php echo $userId ?>&job_id=<?php echo $jobId?>' class="job-link">
                <div class="card-box">
                    <p class="title"><?php echo $jobInfo['job_title']; ?></p>
                    <p class="freelancer-name">Hired Freelancer: <?php echo $jobInfo['freelancer_name']; ?></p>
                </div>
            </a>
<?php
            $checkExistingRequestQuery = "select * from job_close where job_id=$jobId";
            $checkExistingRequestResult = mysqli_query($connection, $checkExistingRequestQuery);
            if(mysqli_num_rows($checkExistingRequestResult) > 0) {
                $row = mysqli_fetch_assoc($checkExistingRequestResult);
                $requesterId = $row["requester_id"];
                $responderId = $row["responder_id"];
                if($userId == $requesterId) {
                      echo "
            <form action='http://localhost/freelancing-website/config/helper/deleteJobRequest.php' method='POST'>
                <input type='hidden' name='job_id' value='$jobId'>
                <input type='hidden' name='freelancer_user_id' value='$applicantUserId'>
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
                        <form action='http://localhost/freelancing-website/config/helper/closeJob.php?job_id=$jobId&receiver_id=$applicantUserId' method='POST'>
                            <input class='close-job' type='submit' value='Request Job Close'>
                        </form>";
            }
?>
        </div>
<?php
    }
} else {
    echo "No jobs found.";
}
?>
</main>
</html>


