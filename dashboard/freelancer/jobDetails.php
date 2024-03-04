
<?php
session_start(); // Start the session if not already started

// Check if the user is logged in and is a Freelancer
if (isset($_SESSION["user_id"]) && isset($_SESSION["login"]) && $_SESSION["user_type"] == "Freelancer") {
    include("../../config/database/databaseConfig.php");

    $userId = $_SESSION["user_id"];

    $getUserStatusQuery = "select user_status from user where user_id ='$userId' ";

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
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Job Information</title>
                <!-- Add any additional styles or scripts here -->
            </head>
            <body>
                <div>
                    <h1>Job Information</h1>
                    <div>
                        <h2><?php echo $jobData["job_title"]; ?></h2>
                        <p>Description: <?php echo $jobData["job_description"]; ?></p>
                        <p>Status: <?php echo $jobData["job_status"]; ?></p>
                        <p>Budget: <?php echo $jobData["job_budget"]; ?></p>
                        <p>Duration: <?php echo $jobData["job_duration"]; ?></p>
                    </div>
                       <form method="POST" action=""> 
    <input type="submit" name="apply" value="Apply" <?php if($userStatus != 1) echo "disabled"; ?>>
</form>

                </div>
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
    echo "<p>Unauthorized access.</p>";
}
?>


<?php

$getClientIdQuery= "SELECT user_id FROM job WHERE job_status = 1 and job_id = '$jobId'";
            $getClientIdResult = mysqli_query($connection, $getJobDataQuery);

            $clientData = mysqli_fetch_assoc($getClientIdResult);

            $clientUserId = $clientData["user_id"];


            $freelancerUserId = $_SESSION["user_id"];


            
             if(isset($_POST["apply"])) {
   

    
  
       

 
        $checkApplicationQuery = "SELECT * FROM job_application WHERE job_id = '$jobId' AND freelancer_user_id = '$freelancerUserId' and client_user_id = '$clientUserId'";
        $checkApplicationResult = mysqli_query($connection, $checkApplicationQuery);

        if(mysqli_num_rows($checkApplicationResult) > 0) {
            echo "You have already applied for this job.";
        } else {
            // Query to insert the application into the database
            $applyQuery = "INSERT INTO job_application (job_id, client_user_id, freelancer_user_id) VALUES ('$jobId', '$clientUserId', '$freelancerUserId')";

            if(mysqli_query($connection, $applyQuery)) {
               header("Location:http://localhost/freelancing-website/dashboard/freelancer/freelancerDashboard.php ");
            } else {
                echo "Error: " . mysqli_error($connection);
            }
        }
    } 
            ?>