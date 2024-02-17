

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        nav {
            width: 90vw;
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        .job-card {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
   <nav class="nav">
    <p>Logo</p>
    <div>
        <a href="http://localhost/freelancing-website/dashboard/freelancer/freelancerProfile.php">My Profile</a>
        <a href="http://localhost/freelancing-website/dashboard/freelancer/freelancerJobHistory.php">Job History</a>
        <a href="http://localhost/freelancing-website/dashboard/freelancer/myJobs.php">My Jobs</a>
        <a href="http://localhost/freelancing-website/dashboard/logout.php">Logout</a>
    </div>
   </nav>
</body>
</html>


<?php
session_start(); // Start the session if not already started

// Check if the user is logged in and is a Freelancer
if (isset($_SESSION["user_id"]) && isset($_SESSION["login"]) && $_SESSION["user_type"] == "Freelancer") {

   
    include("../../config/database/databaseConfig.php");




    // Query to select jobs with status 1
    $sql = "SELECT * FROM job WHERE job_status = 1";
    $result = mysqli_query($connection, $sql);

    

    // Check if there are any jobs
    if (mysqli_num_rows($result) > 0) {
        // Output each job as a card
        while ($row = mysqli_fetch_assoc($result)) {

echo '<a href="jobDetails.php?job_id=' . $row['job_id'] . '">';
echo "<div class='job-card'>";
echo "<h2>" . $row['job_title'] . "</h2>";
echo "<p>" . $row['job_description'] . "</p>";
echo "<p>Budget: $" . $row['job_budget'] . "</p>";
echo "<p>Duration: " . $row['job_duration'] . "</p>";
echo "<p>Job Status: " . $row['job_status'] . "</p>";
// You can add more details here as needed
echo "</div>";
echo "</a>";

        }
    } else {
        echo "No jobs found.";
    }

    // Close the database connection
    mysqli_close($connection);

} else {
    // Redirect the user if not logged in or not a Freelancer
    $_SESSION = [];
    session_destroy();
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
    exit; // Make sure to exit after header redirection
}
?>