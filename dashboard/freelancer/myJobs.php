<?php
session_start();

include("../../config/database/databaseConfig.php");

$userId = $_SESSION["user_id"];

if (isset($_SESSION["user_id"]) && isset($_SESSION["login"])) {

    if($_SESSION["user_type"] != "Freelancer") {
        $_SESSION = [];
        session_destroy();
        header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
        exit;
    }

    $userId = $_SESSION["user_id"];

    $getFreelancerStatusQuery = "SELECT user_status, user_verification_try FROM user WHERE user_id='$userId'";

    $getFreelancerStatusResult = mysqli_query($connection, $getFreelancerStatusQuery);

    if(mysqli_num_rows($getFreelancerStatusResult) > 0) {
        $row = mysqli_fetch_assoc($getFreelancerStatusResult);
        $userStatus = $row['user_status'];
        $userVerificationTry = $row["user_verification_try"];

     
    }

}else{
    $_SESSION = [];
        session_destroy();
        header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
        exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>  <link rel="stylesheet" href="freelancerDashboard.css">
   
</head>
<body>
    <?php include ("freelancerHeader.html");
?>

<main>
    <?php


$userType = $_SESSION["user_type"];

$getAppliedJobHistoryQuery = "SELECT job_id, ja_status, client_user_id FROM job_application WHERE freelancer_user_id='$userId' and ja_status= 1";
$getAppliedJobHistoryResult = mysqli_query($connection, $getAppliedJobHistoryQuery);



if (mysqli_num_rows($getAppliedJobHistoryResult) > 0) {

    while ($row = mysqli_fetch_assoc($getAppliedJobHistoryResult)) {
        $jobId = $row['job_id'];
        $clientUserId = $row["client_user_id"];

  
        $getJobDetailsQuery = "SELECT * FROM job WHERE job_id='$jobId' and job_status=1";
        $getJobDetailsResult = mysqli_query($connection, $getJobDetailsQuery);

        if ($getJobDetailsResult && mysqli_num_rows($getJobDetailsResult) > 0) {
            $jobDetails = mysqli_fetch_assoc($getJobDetailsResult);

            $jobId = $jobDetails["job_id"];
?>

<div class="job-card">
    
     
          <a href='http://localhost/freelancing-website/communication/chatbox.php?job_id=<?php echo $jobId?>&toUser=<?php echo $clientUserId?>' class='job-link'>
            <div class="card-box">
<p class="title"><?php echo $jobDetails['job_title']; ?>
</p>
            </div>
        </a>

        <?php
   
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
           echo "No Jobs Found";
        }
    }
} else {
    echo "No Jobs Found";
}
?> 


  

</main>
    
</body>
</html>



