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
    
    </style>
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
    
            <?php
    if(($userStatus == 0)){
        echo    " <div class='unverified-box'>";
        echo "<p class='unverified'>Your account isn't verified. Please wait to be verified.";
        echo "</div>";
    }else if($userStatus == 2){
           echo    " <div class='unverified-box'>";
        echo "<p class='unverified'>Your account verification was rejected. Please resubmit your profile with genuine documents to get verified.";
        echo "</div>";
    }

    ?>
    

<div class="create-filter">
   

    <h2>My Jobs</h2>
    <div> <button id="createJob" class="createJobModal" <?php if(isset($disableCreateJobButton) && $disableCreateJobButton) echo 'disabled'; ?>>Create Job</button>
    <select id="userTypeSelect" onchange="redirectToSelected()">
        <option value="">Filter By</option>
    </select></div>
   
</div>

<?php include("createJob.php");?>

<?php
if(mysqli_num_rows($getJobDataResult) > 0){
    while($jobInfo = mysqli_fetch_assoc($getJobDataResult)) {
        ?>
        <a href="myJobInfo.php?job_id=<?php echo $jobInfo['job_id']; ?>&user_id=<?php echo $userId; ?>" class="job-link">
            <div class="job-card">
                <div class="card-box">
                    <p class="title"><?php echo $jobInfo['job_title']; ?></p class="title">
                    <div class="price-duration">
                        <p>Rs. <?php echo $jobInfo['job_budget']; ?> - Duration: <?php echo $jobInfo['job_duration']; ?></p>
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
