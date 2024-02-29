
<?php

include("../../config/database/databaseConfig.php");

session_start();

if (isset($_GET["freelancer_user_id"]) && isset($_SESSION["login"]) && isset($_GET["user_type"]) && isset($_GET["client_user_id"])) {
    $freelancerUserId = $_GET["freelancer_user_id"];
    $userType = $_GET["user_type"];
    $clientUserId = $_GET["client_user_id"];
    $jobId = $_GET["job_id"];

    $getUsersQuery = "SELECT * FROM user WHERE user_id = '$freelancerUserId'";
    $getUsersResult = mysqli_query($connection, $getUsersQuery);

    $checkJAStatusQuery = "SELECT ja_status FROM job_application WHERE client_user_id='$clientUserId' AND freelancer_user_id='$freelancerUserId' AND job_id='$jobId'";
    $checkJAStatusResult = mysqli_query($connection, $checkJAStatusQuery);
    $row = mysqli_fetch_assoc($checkJAStatusResult);
    $jobStatus = $row["ja_status"];

    if (mysqli_num_rows($getUsersResult) > 0) {
        while ($row = mysqli_fetch_assoc($getUsersResult)) {
            $userFirstName = $row['user_first_name'];
            $userLastName = $row['user_last_name'];
            $userEmail = $row['user_email'];
            $userPhoneNumber = $row['user_phone_number'];
            $userType = $row['user_type'];
            $userPhoto = $row['user_photo'];
        }
    }

    $getFreelancersQuery = "SELECT * FROM freelancer WHERE user_id = '$freelancerUserId'";
    $getFreelancersResult = mysqli_query($connection, $getFreelancersQuery);
    if (mysqli_num_rows($getFreelancersResult) > 0) {
        while ($row = mysqli_fetch_assoc($getFreelancersResult)) {
            $freelancerIdentityPhoto = $row["freelancer_identity_photo"];
            $freelancerVerificationPhoto = $row["freelancer_verification_photo"];
            $freelancerBio = $row["freelancer_bio"];
            $freelancerCV = $row["freelancer_cv"];
        }
    }
} else {
    $_SESSION = [];
    session_destroy();
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="displayProfile.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
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
        <div class="card">
         
            <div class="info-details">
                   <div class="user-photo">
                <img class="user-pic" src="../../userAuth/userRegistration/<?php echo $userPhoto; ?>" alt="User Photo">
            </div>
            
</div>
                       <div class="info-details-text">
                             <h2>Freelancer Information</h2>
                <p><strong>Name: </strong>   <?php echo $userFirstName . ' ' . $userLastName; ?></p>
                    <p><strong>Email: </strong> <?php echo $userEmail; ?></p>
                    <p><strong>Phone Number: </strong> <?php echo $userPhoneNumber; ?></p>
                    <p><strong>User Type: </strong> <?php echo $userType; ?></p>
                    <p class="bio"><strong>Freelancer Bio:</strong> <?php echo $freelancerBio; ?></p>
                      <p><a href="../../userAuth/userRegistration/freelancerRegistration/<?php echo $freelancerIdentityPhoto; ?>" target="_blank">View Identity Photo</a></p>
                   
                        <p><a href="../../userAuth/userRegistration/freelancerRegistration/<?php echo $freelancerVerificationPhoto; ?>" target="_blank">View Verification Photo</a></p>
                   
                    <p><a href="../../userAuth/userRegistration/freelancerRegistration/<?php echo $freelancerCV; ?>" target="_blank">View Cirriculum Vitae</a></p>


                    <h3>Freelancer Job History</h3>

                    <?php
// Query to fetch job applications with ja_status equal to 1
$freelancerJobHistory = "SELECT ja.job_id, j.job_title, j.job_duration, j.job_budget
                         FROM job_application ja
                         INNER JOIN job j ON ja.job_id = j.job_id
                         WHERE ja.freelancer_user_id='$freelancerUserId' AND ja.ja_status = 1";
$freelancerJobHistoryResult = mysqli_query($connection, $freelancerJobHistory);

if (mysqli_num_rows($freelancerJobHistoryResult) > 0) { 

    // Display table headers
    echo "<table>";
    echo "<tr>";
    echo "<th>Job Title</th>";
    echo "<th>Job Duration</th>";
    echo "<th>Job Budget</th>";
    echo "</tr>";

    while ($row = mysqli_fetch_assoc($freelancerJobHistoryResult)) {
        // Display job details
        echo "<tr>";
        echo "<td>" . $row["job_title"] . "</td>";
        echo "<td>" . $row["job_duration"] . "</td>";
        echo "<td>" . "Rs. " .$row["job_budget"] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
}else{
    echo "No previous jobs.";
}


?>
                   
            </div>
            <div class="info-details-text">
                 <div class="action-buttons">
            <form method="POST">
                <input type="hidden" class="hire" name="user_id" value="<?php echo $freelancerUserId; ?>">
                <input type="submit" class="hire" name="hire" value="Hire">
            </form>
            <form method="POST">
                <input type="hidden" class="reject" name="user_id" value="<?php echo $freelancerUserId; ?>">
                <input type="submit" class="reject" name="reject" value="Reject">
            </form>
             </div>
            </div>
            
        </div>
        
    </main>
</body>
</html>

<?php

if (isset($_POST["hire"])) {
    $hireQuery = "UPDATE job_application SET ja_status = 1 WHERE freelancer_user_id = '$freelancerUserId' AND client_user_id = '$clientUserId' AND job_id = '$jobId'";
    $hireResult = mysqli_query($connection, $hireQuery);
    if ($hireResult) {
        header("Location: http://localhost/freelancing-website/dashboard/client/activeJob.php");
    } else {
        echo "Error hiring user: " . mysqli_error($connection);
    }
} else if (isset($_POST["reject"])) {
    $rejectQuery = "UPDATE job_application SET ja_status = 2 WHERE freelancer_user_id = '$freelancerUserId' AND client_user_id = '$clientUserId' AND job_id = '$jobId'";
    $rejectResult = mysqli_query($connection, $rejectQuery);
    if ($rejectResult) {
        header("Location: http://localhost/freelancing-website/dashboard/client/clientDashboard.php");
    } else {
        echo "Error rejecting user: " . mysqli_error($connection);
    }
}

?>
