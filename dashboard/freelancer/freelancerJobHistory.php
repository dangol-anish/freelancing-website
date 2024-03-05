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


$userId = $_SESSION["user_id"];



$getAppliedJobHistoryQuery = "SELECT job_id, ja_status, client_user_id FROM job_application WHERE freelancer_user_id='$userId'";
$getAppliedJobHistoryResult = mysqli_query($connection, $getAppliedJobHistoryQuery);
?>



        
<link rel="stylesheet" href="freelancerDashboard.css">

<?php include("freelancerHeader.html");?>


<main>
        <?php
if (mysqli_num_rows($getAppliedJobHistoryResult) > 0) {
    echo "<h1 class='heading'>My Job History</h1>";
    while ($row = mysqli_fetch_assoc($getAppliedJobHistoryResult)) {
        $jobId = $row['job_id'];
        $jobApplicationStatus = $row['ja_status'];
        $toUser = $row["client_user_id"];

        if($jobApplicationStatus == 0){
            $jobApplication = "In Review";
        } elseif ($jobApplicationStatus == 1){
            $jobApplication = "Accepted";
        } elseif ($jobApplicationStatus == 2){
            $jobApplication = "Rejected";
        } else {
            $jobApplication = "Error";
        }

        // Retrieve job details from the "job" table
        $getJobDetailsQuery = "SELECT * FROM job WHERE job_id='$jobId'";
        $getJobDetailsResult = mysqli_query($connection, $getJobDetailsQuery);

        

        if ($getJobDetailsResult) {
            $jobDetails = mysqli_fetch_assoc($getJobDetailsResult);
?>

             <div class="job-card">
                 <div class="card-box">
                    <p class="title"><?php echo $jobDetails['job_title']; ?></p>
                    <p class="freelancer-name"><?php echo $jobApplication?></p>
                </div>
 
        </div>


       
       
            <!-- echo $jobApplication;
            echo '</a>';
        } else {
            echo "Error fetching job details";
        }
    }
} else {
    echo "No Jobs Found";
}

} -->

<?php
        }
    }
}
}else{
     $_SESSION = [];
        session_destroy();
        header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
        exit;
}
?>

 </main>
