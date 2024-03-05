
<?php
session_start(); 

include("../../config/database/databaseConfig.php");

if (isset($_SESSION["user_id"]) && isset($_SESSION["login"])) {

    if($_SESSION["user_type"] != "Freelancer") {
        $_SESSION = [];
        session_destroy();
        header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
        exit;
    }
} else {
     $_SESSION = [];
        session_destroy();
        header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
        exit;
}

$userId = $_SESSION["user_id"];

// Fetch closed jobs associated with the current user
$getJobDataQuery = "
  SELECT 
  j.*
   ,
    u.user_first_name,
    u.user_last_name
FROM 
    job_close jc
JOIN 
    job j ON jc.job_id = j.job_id
JOIN 
    user u ON j.user_id = u.user_id
WHERE 
    jc.requester_id = $userId OR jc.responder_id = $userId;

";

$getJobDataResult = mysqli_query($connection, $getJobDataQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="freelancerDashboard.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include("freelancerHeader.html") ?>

    <main>
            <h1 class="heading">My Closed Jobs</h1>
         <?php if(mysqli_num_rows($getJobDataResult) > 0): ?>
            <?php while($jobInfo = mysqli_fetch_assoc($getJobDataResult)):
             $jobId = $jobInfo["job_id"];
                
                
                ?>
               
                <div class="job-card">
                    <div class="card-box">
                        <p class="job-close-title"><?php echo $jobInfo['job_title']; ?></p>
                        <p class="freelancer-name">Client: <?php echo $jobInfo['user_first_name'] . ' ' . $jobInfo['user_last_name']; ?></p>
                    </div>
                    <div class="rated">

                    <?php 
                    $getRatings =" select rating from freelancer_rating where job_id='$jobId' and freelancer_user_id='$userId'";

                    $getRatingsResult = mysqli_query($connection, $getRatings);

                    if(mysqli_num_rows($getRatingsResult)>0){
                        $row = mysqli_fetch_assoc($getRatingsResult);
 echo "<p>". "You were rated " . $row["rating"] . " &#9733; for this job"."</p>";
                    }else{
                        echo "<p> You haven't been rated yet for this job.</p>";
                    }

                    ?>
                    </div>
                </div> 
            <?php endwhile; ?>
         <?php endif; ?>
    </main>
    
</body>
</html>
