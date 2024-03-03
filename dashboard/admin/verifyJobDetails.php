<?php
session_start(); // Start the session if not already started


$jobId = $_GET["job_id"];

if( isset($_SESSION["user_id"]) && isset($_SESSION["login"])) {

    if($_SESSION["user_type"] != "Admin"){
    $_SESSION = [];
    session_destroy();
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");

    }
    
}else{
     $_SESSION = [];
    session_destroy();
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");

}


include("../../config/database/databaseConfig.php");


$getClosedJobsQuery = "select * from job where job_status = 0 and job_id = '$jobId'";
$getClosedJobsResult = mysqli_query($connection, $getClosedJobsQuery);



while ($row = mysqli_fetch_assoc($getClosedJobsResult)) {
    $jobId = $row["job_id"];
  $jobTitle = $row["job_title"];
     $jobDescription=$row["job_description"];
   $jobBudget = $row["job_budget"];
    $jobDuration = $row["job_duration"];

      



    
}

$getSkills = "SELECT s.skill_name
FROM job_skill js
JOIN skill s ON js.skill_id = s.skill_id
WHERE js.job_id = $jobId;";

$getSkillsResult = mysqli_query($connection, $getSkills);


?>

<?php include 'adminHeader.html'; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="adminDashboard.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<main class="job-details-main">
    <h2 class="job-details-heading"><?php echo $jobTitle ?></h2>
  <div class="price-duration">
                        <p>Rs. <?php echo $jobBudget; ?> - Duration: <?php echo $jobDuration; ?></p>
                    </div>

                    <p>
                        <?php echo $jobDescription?>
                    </p>

                    <h3>Required Skills</h3>
                       <div class="skill-list">
                    <?php
                    while($row = mysqli_fetch_assoc($getSkillsResult)) {
    echo "<p class='skill'>" . $row['skill_name'] . "</p>";
}
                    ?>
                    </div>

</main>


  
    
</body>
</html>