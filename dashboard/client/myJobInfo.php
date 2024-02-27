<?php
session_start(); // Start the session if not already started

include("../../config/database/databaseConfig.php");

if(isset($_SESSION["user_id"]) && isset($_SESSION["login"]) && isset($_GET["job_id"])) {
    if($_SESSION["user_type"] != "Client") {
        $_SESSION = [];
        session_destroy();
        header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
        exit;
    }

    $userId = $_SESSION["user_id"];
    $jobId = $_GET["job_id"];

    $getJobDataQuery = "select * from job where job_id = '$jobId'";
    $getJobDataResult = mysqli_query($connection, $getJobDataQuery);

    $getJobApplicationStatusQuery = "select ja_status from job_application where job_id = '$jobId'";
    $getJobApplicationStatusResult = mysqli_query($connection, $getJobApplicationStatusQuery);

    $getUserDataQuery = "select user_first_name, user_last_name from user where user_id='$userId'";
    $getUserDataResult = mysqli_query($connection, $getUserDataQuery);

    if(mysqli_num_rows($getJobDataResult) > 0 && mysqli_num_rows($getUserDataResult)) {
        $job = mysqli_fetch_assoc($getJobDataResult);
        $user =  mysqli_fetch_assoc($getUserDataResult);

        $jobTitle = $job["job_title"];
        $jobDescription = $job["job_description"];
        $jobBudget = $job["job_budget"];
        $jobDuration = $job["job_duration"];
        $jobStatusNumber = $job["job_status"];
        $userId = $job["user_id"];
        $userFirstName = $user["user_first_name"];
        $userLastName = $user["user_last_name"];
        $userName = $userFirstName . $userLastName;

        if($jobStatusNumber == "1") {
            $jobStatus = "Open";
        } elseif($jobStatusNumber == "2") {
            $jobStatus = "Closed";
        } elseif($jobStatusNumber == "0") {
            $jobStatus = "In Review";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="clientDashboard.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
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

<?php if(isset($jobTitle)): ?>
<main>
    <section class="container-one">
        <div>
            <h2 class="job-heading"><?php echo $jobTitle; ?></h2>
        </div>
        <div>
            <form class="job-delete" action="" method="POST">
                <input class="createJobModal" type="submit" value="Delete" name="delete">
            </form>
        </div>
    </section>
    <hr>
    <section class="container-two">
        <div class="container-two-one">
            <p><i>Created by: <?php echo $userName ?></i></p>
            <div class="price-duration">
                Rs. <?php echo $jobBudget; ?> -
                Duration: <?php echo $jobDuration; ?>
            </div>
            <p class="price-duration">Status: <?php echo $jobStatus; ?></p>
            <p class="job-description"><b>Description</b></p>
            <p class="text-justify"><?php echo $jobDescription; ?></p>
            <br>
            <?php if(isset($getUserDataResult) && mysqli_num_rows($getUserDataResult) > 0): ?>
                <?php $userData = mysqli_fetch_assoc($getUserDataResult); ?>
            <?php endif; ?>
        </div>
        <div class="applicants">
            <?php
            echo "<h1>Applicants</h1>";
            echo "<br>";
            $getJobApplyDataQuery = "select * from job_application where job_id='$jobId' and ja_status <>2";
            $getJobApplyDataResult = mysqli_query($connection, $getJobApplyDataQuery);

            if($jobStatusNumber == 1){
                if(mysqli_num_rows($getJobApplicationStatusResult) > 0) {
                    $row = mysqli_fetch_assoc($getJobApplicationStatusResult);
                    $jaStatus = $row["ja_status"];
                    if($jaStatus == 1){
                        echo "<p class='text-left'>You have already hired a freelancer for this job.</p>";
                    }else{
                        if(mysqli_num_rows($getJobApplyDataResult) > 0) {
                            while ($row = mysqli_fetch_assoc($getJobApplyDataResult)) {
                                $applicantUserId = $row['freelancer_user_id'];
                                $getUserDataQuery = "select * from user where user_id = '$applicantUserId'";
                                $getUserDataResult = mysqli_query($connection, $getUserDataQuery);
                                $userData = mysqli_fetch_assoc($getUserDataResult);
                                $userName = $userData["user_first_name"] . " ". $userData["user_last_name"];
                                $userType = $userData["user_type"];
                                $userLink = "php?user_id=" . $applicantUserId . "&user_type=" . $userType;

                                // Create a clickable card for user name
                                echo '<div class="container-two-two">';
                                echo "<a href='http://localhost/freelancing-website/dashboard/freelancer/displayProfile.php?freelancer_user_id=$applicantUserId&user_type=$userType&client_user_id=$userId&job_id=$jobId' class='user-link'>";
                                echo $userName;
                                echo '</a>';
                                echo '</div>';
                            }
                        } else {
                            echo "<p class='text-left'>There are no applications for this job yet.</p>";
                        }
                    }
                }
            }else if($jobStatusNumber == 2){
                echo "<p class='text-left'>The job has already been closed.</p>";
            }else if($jobStatusNumber == 0){
                echo "<p class='text-left'>The job is currently is in review.</p>";
            }
            ?>
        </div>
    </section>
</main>
<?php endif; ?>

<?php
