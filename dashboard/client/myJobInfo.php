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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Job Details</title>
<style>
.card {
  width: 300px;
  border: 1px solid #ccc;
  border-radius: 5px;
  padding: 20px;
  margin: 20px;
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
}

.card h2 {
  margin-top: 0;
}

.card p {
  margin: 10px 0;
}

.card .status {
  font-weight: bold;
  color: #007bff;
}

.card .budget {
  font-weight: bold;
  color: #28a745;
}

.card .duration {
  font-weight: bold;
  color: #dc3545;
}

.card .user {
  font-style: italic;
}
</style>
</head>
<body>

<?php if(isset($jobTitle)): ?>
<div class="card">
  <h2><?php echo $jobTitle; ?></h2>
  <p><strong>Description:</strong> <?php echo $jobDescription; ?></p>
  <p><strong>Budget:</strong> <span class="budget">Rs. <?php echo $jobBudget; ?></span></p>
  <p><strong>Duration:</strong> <span class="duration"><?php echo $jobDuration; ?> </span></p>
  <p><strong>Status:</strong> <span class="status"><?php echo $jobStatus; ?></span></p>
  <?php if(isset($getUserDataResult) && mysqli_num_rows($getUserDataResult) > 0): ?>
    <?php $userData = mysqli_fetch_assoc($getUserDataResult); ?>
    <p><strong>User:</strong> <span class="user"><?php echo $userName ?></span></p>
  <?php endif; ?>

  <form action="" method="POST">
    <input type="submit" value="Delete" name="delete">
  </form>
</div>
<?php endif; ?>

<?php
if(isset($_POST["delete"])){
    $deleteJobQuery = "delete from job where job_id='$jobId' and user_id='$userId'";
    $deleteJobResult = mysqli_query($connection, $deleteJobQuery);
    
    if($deleteJobResult) {
        // Check if any rows were affected
        if(mysqli_affected_rows($connection) > 0) {
            header("Location: http://localhost/freelancing-website/dashboard/client/clientDashboard.php");
            exit;
        } else {
            echo "No rows were deleted.";
        }
    } else {
        echo "Error while deleting: " . mysqli_error($connection);
    }
}

$getJobApplyDataQuery = "select * from job_application where job_id='$jobId' and ja_status <>2";
$getJobApplyDataResult = mysqli_query($connection, $getJobApplyDataQuery);

if($jobStatusNumber == 1){
  
echo "<h1>Applications</h1>";

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
        echo '<div class="card">';
        echo "<a href='http://localhost/freelancing-website/dashboard/freelancer/displayProfile.php?freelancer_user_id=$applicantUserId&user_type=$userType&client_user_id=$userId&job_id=$jobId' class='user-link'>";
        echo $userName;
        echo $userType;
        echo '</a>';
        echo '</div>';
    }
} else {
    echo "No Applications yet";
}

}else if($jobStatusNumber == 2){
  echo "Job is closed";

}else if($jobStatusNumber == 0){
  echo "Job In Review";
}

?>
</body>
</html>
