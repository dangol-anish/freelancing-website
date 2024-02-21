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
    WHERE j.user_id='$userId' AND ja.ja_status = 1";
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

<header>
    <img class="logo-image" src="../../assets/logo/test.png" alt="logo">
    <nav> 
        <a class="header-links" href="http://localhost/freelancing-website/dashboard/client/clientDashboard.php">Home</a>
        <a class="header-links" href="http://localhost/freelancing-website/dashboard/client/activeJob.php">Active Jobs</a>
        <a class="header-links" href="http://localhost/freelancing-website/dashboard/client/clientProfile.php">My Profile</a>
        <a id="logout-btn" class="header-links" href="http://localhost/freelancing-website/dashboard/logout.php">Logout</a>
    </nav>
</header>

<main>



<?php include("createJob.php");?>


<?php
if(mysqli_num_rows($getJobDataResult) > 0){
    while($jobInfo = mysqli_fetch_assoc($getJobDataResult)) {

        $applicantUserId = $jobInfo["freelancer_user_id"];
      
        $jobId = $jobInfo["job_id"];
        ?>
       

         <a href='http://localhost/freelancing-website/dashboard/freelancer/displayProfile.php?freelancer_user_id=<?php echo $applicantUserId?>&user_type=<?php echo $userType?>&client_user_id=<?php echo $userId ?>&job_id=<?php echo $jobId?>' class="job-link">
            <div class="job-card">
                <div class="card-box">
 <p class="title"><?php echo $jobInfo['job_title']; ?></p>
            <p class="freelancer-name">Hired Freelancer: <?php echo $jobInfo['freelancer_name']; ?></p>
                </div>
            </div>
    </a>
        <?php
    }
} else {
    echo "No jobs found.";
}
?>


</main>

</body>
</html>
