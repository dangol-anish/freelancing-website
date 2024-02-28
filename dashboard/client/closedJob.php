<?php
session_start();
include("../../config/database/databaseConfig.php");

// Redirect to login if session variables are not set
if(!isset($_SESSION["user_id"]) || !isset($_SESSION["login"]) || $_SESSION["user_type"] != "Client") {
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
    exit;
}

$userId = $_SESSION["user_id"];

// Fetch user status
$getClientStatusQuery = "SELECT user_status FROM user WHERE user_id='$userId'";
$getClientStatusResult = mysqli_query($connection, $getClientStatusQuery);
$userStatus = 0; // Default user status
if(mysqli_num_rows($getClientStatusResult) > 0) {
    $row = mysqli_fetch_assoc($getClientStatusResult);
    $userStatus = $row['user_status'];
}

// Disable create job button based on user status
$disableCreateJobButton = ($userStatus == 2 || $userStatus == 0);

$userType = $_SESSION["user_type"];

// Fetch jobs associated with the current user where ja_status is 1
$getJobDataQuery = "
    SELECT j.*, ja.*, CONCAT(u.user_first_name, ' ', u.user_last_name) AS freelancer_name, ja.job_id AS job_id, ja.freelancer_user_id AS freelancer_user_id
    FROM job j
    INNER JOIN job_application ja ON j.job_id = ja.job_id
    INNER JOIN user u ON ja.freelancer_user_id = u.user_id
    WHERE j.user_id='$userId' AND ja.ja_status = 1 and j.job_status=2";
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
        <a href="http://localhost/freelancing-website/dashboard/client/clientDashboard.php"><img class="logo-image" src="../../assets/logo/test.png" alt="logo"></a>
        <nav> 
            <a class="header-links" href="http://localhost/freelancing-website/dashboard/client/clientDashboard.php">Home</a>
            <a class="header-links" href="http://localhost/freelancing-website/dashboard/client/activeJob.php">Active Jobs</a>
             <a class="header-links" href="http://localhost/freelancing-website/dashboard/client/closedJob.php">Closed Jobs</a>
            <a class="header-links" href="http://localhost/freelancing-website/dashboard/client/clientProfile.php">My Profile</a>
            <a id="logout-btn" class="header-links" href="http://localhost/freelancing-website/dashboard/logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <?php include("createJob.php"); ?>
        <?php if(mysqli_num_rows($getJobDataResult) > 0): ?>
            <?php while($jobInfo = mysqli_fetch_assoc($getJobDataResult)): ?>
                <?php
                $applicantUserId = $jobInfo["freelancer_user_id"];
                $jobId = $jobInfo["job_id"];
                ?>
                <div class="job-card">
                    
                        <div class="card-box">
                            <p class="job-close-title"><?php echo $jobInfo['job_title']; ?></p>
                            <p class="freelancer-name">Hired Freelancer: <?php echo $jobInfo['freelancer_name']; ?></p>
                        </div>
             <form  action='' method='POST'>
                <input class="card-box-closed" class="card-box-closed" type='submit' value='Rate the freelancer'>
            </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No jobs found.</p>
        <?php endif; ?>
    </main>
</body>
</html>
