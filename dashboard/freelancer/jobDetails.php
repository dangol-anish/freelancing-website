<?php
session_start(); // Start the session if not already started

// Check if the user is logged in and is a Freelancer
if (isset($_SESSION["user_id"]) && isset($_SESSION["login"])) {
    
    include("../../config/database/databaseConfig.php");

    $userId = $_SESSION["user_id"];

    $getUserStatusQuery = "SELECT user_status FROM user WHERE user_id ='$userId' ";

    $getUserStatusResult = mysqli_query($connection, $getUserStatusQuery);

    $row = mysqli_fetch_assoc($getUserStatusResult);

    $userStatus = $row["user_status"];

    // Check if the job_id is set in the URL
    if(isset($_GET["job_id"])) {
        $jobId = $_GET["job_id"];

        // Query to select the job with the given job_id and status 1
        $getJobDataQuery = "SELECT * FROM job WHERE job_status = 1 AND job_id = '$jobId'";
        $getJobDataResult = mysqli_query($connection, $getJobDataQuery);

        // Check if there are any jobs
        if(mysqli_num_rows($getJobDataResult) > 0) {
            $jobData = mysqli_fetch_assoc($getJobDataResult);
            
            // Get client user ID
            $clientUserId = $jobData["user_id"];
            $freelancerUserId = $_SESSION["user_id"];

            // Check if the user has already applied for this job
            $checkApplicationQuery = "SELECT * FROM job_application WHERE job_id = '$jobId' AND freelancer_user_id = '$freelancerUserId' AND client_user_id = '$clientUserId'";
            $checkApplicationResult = mysqli_query($connection, $checkApplicationQuery);

            $alreadyApplied = mysqli_num_rows($checkApplicationResult) > 0;
            
            // Handle form submission
            if(isset($_POST["apply"])) {
                if(!$alreadyApplied && $userStatus == 1) {
                    // Query to insert the application into the database
                    $applyQuery = "INSERT INTO job_application (job_id, client_user_id, freelancer_user_id) VALUES ('$jobId', '$clientUserId', '$freelancerUserId')";

                    if(mysqli_query($connection, $applyQuery)) {
                        header("Location: http://localhost/freelancing-website/dashboard/freelancer/freelancerDashboard.php");
                        exit;
                    } else {
                        echo "Error: " . mysqli_error($connection);
                    }
                } else {
                    echo "You are not eligible to apply for this job.";
                }
            }
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Job Information</title>
                <link rel="stylesheet" href="freelancerDashboard.css">
            </head>
            <body>
                <main class="job-details-main">
                    <h1>Job Information</h1>
                    <div>
                        <div class="job-details-top">
                            <h2>Job Title: <?php echo $jobData["job_title"]; ?></h2>
                            <form method="POST" action=""> 
                                <input type="submit" name="apply" value="Apply" <?php if($alreadyApplied || $userStatus != 1) echo "hidden"; ?>>
                                <?php if($alreadyApplied) echo "Already applied!"; ?>
                            </form>
                        </div>
                        <p>Status: <?php echo $jobData["job_status"]; ?></p>
                        <p>Budget: <?php echo $jobData["job_budget"]; ?></p>
                        <p>Duration: <?php echo $jobData["job_duration"]; ?></p>
                        
                        <p>Description: <?php echo $jobData["job_description"]; ?></p>
                    </div>
                </main>
            </body>
            </html>
            <?php
        } else {
            echo "<p>No job found with the provided job ID.</p>";
        }
    } else {
        echo "<p>No job ID provided.</p>";
    }
} else {
    $_SESSION = [];
    session_destroy();
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
    exit;
}
?>
