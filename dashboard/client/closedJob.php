
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
$userStatus = 0; 
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
<?php include("clientHeader.html") ?>

    <main>
        <h2 style="padding-bottom:20px">Active Jobs</h2>
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
<?php

$checkExistingRating = "SELECT * FROM freelancer_rating WHERE job_id = $jobId";
$checkExistingRatingResult = mysqli_query($connection, $checkExistingRating);

// Check if there are no existing ratings for the job
if(mysqli_num_rows($checkExistingRatingResult) == 0) {
    // If there are no existing ratings, show the rating form
    ?>
    <form id="rateForm" action='' method='POST'>
        <input class="close-job" type='button' value='Rate the freelancer' onclick="openModal()">
    </form>
    <?php
}

?>


      
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No jobs found.</p>
        <?php endif; ?>

        <?php

if(isset($_POST["rate_submit"])){

    $rating = $_POST["rating"];

    // Check if there's already a rating for this freelancer by this client
    $checkRatingQuery = "SELECT * FROM freelancer_rating WHERE job_id = '$jobId' AND freelancer_user_id = '$applicantUserId' AND client_user_id = '$userId'";
    $checkRatingResult = mysqli_query($connection, $checkRatingQuery);

    if(mysqli_num_rows($checkRatingResult) > 0){
        echo "<p>You have already rated this freelancer for this job.</p>";
    } else {
        // If no rating exists, insert the new rating
        $insertRating = "INSERT INTO freelancer_rating (rating, job_id, freelancer_user_id, client_user_id) VALUES ('$rating', '$jobId', '$applicantUserId', '$userId')";
        $insertRatingResult = mysqli_query($connection, $insertRating);

        if($insertRatingResult){
            echo "Successfully rated";
        }
        else{
            echo "Failed";
        }
    }
}
?>
    </main>
 <div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <!-- Modal content -->
        <h2>Rate the freelancer</h2>
        <div class="rating">

        <form action="" method="POST">
     <label  class="star"><input type="radio" name="rating" value="1" checked> &#9733;</label><br>
<label  class="star"><input type="radio" name="rating" value="2"> &#9733; &#9733;</label><br>
<label  class="star"><input type="radio" name="rating" value="3">  &#9733; &#9733; &#9733;</label><br>
<label  class="star"><input type="radio" name="rating" value="4"> &#9733; &#9733; &#9733; &#9733;</label><br>
<label  class="star"><input type="radio" name="rating" value="5"> &#9733; &#9733; &#9733; &#9733; &#9733;</label><br>

<input type="submit" name="rate_submit" value="Rate" class="createJobModal">

      </div>
        </form>
           
  

  
    </div>
</div>

</body>
</html>


 <script>
        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the button that opens the modal
        var btn = document.getElementsByClassName("close-job");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        function openModal() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }


     

    </script>



