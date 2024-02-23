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
    echo "<p>" . $row["job_title"] ."</p>";
    echo "<p>" . $row["job_description"] ."</p>";
    echo "<p>" . $row["job_budget"] ."</p>";
    echo "<p>" . $row["job_duration"] ."</p>";

       echo "<td class='action-btn'>
                    <form class='addjobs' action='' method='post'>
                        <input type='hidden' name='verify_jobs' value='" . $row['job_id'] . "'>
                        <input type='submit' value='Verify'>
                    </form>
                    <form class='addjobs' action='' method='post'>
                        <input  class='reject'  type='hidden' name='delete_jobs' value='" . $row['job_id'] . "'>
                        <input class='reject' type='submit' value='Delete'>
                    </form>
                </td>";


}




// Handle skill verification
if (isset($_POST['verify_jobs'])) {
  
    $query = "UPDATE job SET job_status = 1 WHERE job_id = $jobId";
    $result = mysqli_query($connection, $query);
    if ($result) {
      header("Location: http://localhost/freelancing-website/dashboard/admin/verifyJobs.php");
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}

// Handle skill deletion
if (isset($_POST['delete_jobs'])) {
  
    $query = "DELETE FROM job WHERE job_id = $jobId";
    $result = mysqli_query($connection, $query);
    if ($result) {
        // Redirect to the same page after deletion
      header("Location: http://localhost/freelancing-website/dashboard/admin/verifyJobs.php");
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>
