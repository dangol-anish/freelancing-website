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

// Fetch jobs associated with the current user
$getJobDataQuery = "SELECT * FROM job WHERE user_id='$userId'";
$getJobDataResult = mysqli_query($connection, $getJobDataQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <link rel="stylesheet" href="clientDashboard.css">
    <style>
        /* Style for job cards */
        .job-card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }

        .job-card h3 {
            margin-top: 0;
            margin-bottom: 10px;
        }

        .job-card p {
            margin-top: 0;
            color: #666;
        }

        .job-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-box{
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>

<nav> 
    <h2>LOGO</h2>
    <div>
        <button id="createJob" class="createJobModal" <?php if(isset($disableCreateJobButton) && $disableCreateJobButton) echo 'disabled'; ?>>Create Job</button>
        <a href="http://localhost/freelancing-website/dashboard/client/clientProfile.php">My Profile</a>
        
        <a href="http://localhost/freelancing-website/dashboard/logout.php">Logout</a>
    </div>
</nav>

<?php include("createJob.php");?>

<?php
if(mysqli_num_rows($getJobDataResult) > 0){
    while($row = mysqli_fetch_assoc($getJobDataResult)) {
        ?>
        <a href="myJobInfo.php?job_id=<?php echo $row['job_id']; ?>&user_id=<?php echo $userId; ?>" class="job-link">
            <div class="job-card">
                <h3><?php echo $row['job_title']; ?></h3>
                <div class="card-box">
                    <p><?php echo $row['job_description']; ?></p>
                    <p><?php echo $row['job_duration']; ?></p>
                    <p><?php echo $row['job_budget']; ?></p>
                </div>
            </div>
        </a>
        <?php
    }
} else {
    echo "No jobs found.";
}
?>

<script>
    var modalBtn = document.getElementById("createJob");
    var modal = document.getElementById("updateProfileModal");
    var span = document.getElementsByClassName("close")[0];

    modalBtn.onclick = function() {
        modal.style.display = "block";
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>
