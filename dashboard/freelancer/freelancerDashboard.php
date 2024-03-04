
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

    $getFreelancerStatusQuery = "SELECT user_status, user_verification_try FROM user WHERE user_id='$userId'";

    $getFreelancerStatusResult = mysqli_query($connection, $getFreelancerStatusQuery);

    if(mysqli_num_rows($getFreelancerStatusResult) > 0) {
        $row = mysqli_fetch_assoc($getFreelancerStatusResult);
        $userStatus = $row['user_status'];
        $userVerificationTry = $row["user_verification_try"];

       
    }

    $getJobsQuery = "SELECT * FROM job WHERE job_status = 1";
    $getJobsResult = mysqli_query($connection, $getJobsQuery);
}else{
    $_SESSION = [];
        session_destroy();
        header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
        exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="freelancerDashboard.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Dashboard</title>
   
</head>
<body>

<?php include ("./freelancerHeader.html");
?>

<main>
      <?php
    if(($userStatus == 0)){
        echo    " <div class='unverified-box'>";
        echo "<p class='unverified'>Your account isn't verified. Please wait to be verified.";
        echo "</div>";
    
    }else if($userStatus == 2 && $userVerificationTry>=2){
        echo    " <div class='unverified-box'>";
        echo "<p class='unverified'>Your account verification was rejected too many times. Your account has been blocked.";
        echo "</div>";
    }else if($userStatus == 2){
           echo    " <div class='unverified-box'>";
        echo "<p class='unverified'>Your account verification was rejected. Please resubmit your profile with genuine documents to get verified.";
        echo "</div>";
    }
    ?>
    <br>

    <?php 
    if(mysqli_num_rows($getJobsResult)>0){

    while($jobInfo = mysqli_fetch_assoc($getJobsResult)) {
        ?>
          <a href="jobDetails.php?job_id=<?php echo $jobInfo['job_id']; ?>&user_id=<?php echo $userId; ?>" class="job-link">
           <div class="job-card">
                <div class="card-box">
                    <p class="title"><?php echo $jobInfo['job_title']; ?></p>
                    <div class="price-duration">
                        <p>Rs. <?php echo $jobInfo ['job_budget']; ?> - Duration: <?php echo $jobInfo['job_duration']; ?></p>
                    </div>
                    <p><?php echo limitDescriptionWords($jobInfo['job_description']); ?></p>
                    <div class="skill-list">
                        <?php
                        $jobId = $jobInfo['job_id'];
                        $getJobSkillsQuery = "SELECT skill_id FROM job_skill WHERE job_id='$jobId'";
                        $getJobSkillsResult = mysqli_query($connection, $getJobSkillsQuery);

                        if(mysqli_num_rows($getJobSkillsResult) > 0){
                            while($skillInfo = mysqli_fetch_assoc($getJobSkillsResult)) {
                                // Fetch skill names using skill IDs
                                $skillId = $skillInfo['skill_id'];
                                $getSkillNameQuery = "SELECT skill_name FROM skill WHERE skill_id='$skillId'";
                                $getSkillNameResult = mysqli_query($connection, $getSkillNameQuery);
                                if(mysqli_num_rows($getSkillNameResult) > 0) {
                                    $skillNameInfo = mysqli_fetch_assoc($getSkillNameResult);
                                    echo "<p class='skill'>" . $skillNameInfo['skill_name'] . "</p>";
                                } else {
                                    echo "Skill not found.";
                                }
                            }
                        } else {
                            echo "No skills found.";
                        }
                        ?>
                    </div>

                    <?php
                
                    $jobId = $jobInfo['job_id'];
                    $getApplicantsCountQuery = "SELECT COUNT(*) AS num_applicants FROM job_application WHERE job_id='$jobId'";
                    $getApplicantsCountResult = mysqli_query($connection, $getApplicantsCountQuery);
                    $numApplicants = 0;

                    if(mysqli_num_rows($getApplicantsCountResult) > 0){
                        $applicantsCountInfo = mysqli_fetch_assoc($getApplicantsCountResult);
                        $numApplicants = $applicantsCountInfo['num_applicants'];
                    }

              
                    echo "<p class='noa'>Number of Applicants: $numApplicants</p>";
                    ?>
                </div>
            </div>
        </a>

        <?php
    }
} else {
    echo "No jobs found.";
}
?>
</main>






<?php
function limitDescriptionWords($description, $limit = 40) {
    $words = explode(" ", $description);
    if (count($words) > $limit) {
        $description = implode(" ", array_slice($words, 0, $limit));
        $description .= "...";
    }
    return $description;
}
?>
