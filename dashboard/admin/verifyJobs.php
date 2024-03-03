  <?php
    session_start(); // Start the session if not already started

    if( isset($_SESSION["user_id"]) && isset($_SESSION["login"])) {

        if($_SESSION["user_type"] != "Admin"){
            $_SESSION = [];
            session_destroy();
            header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
        }

    } else {
        $_SESSION = [];
        session_destroy();
        header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
    }

    include("../../config/database/databaseConfig.php");

    $getClosedJobsQuery = "SELECT * FROM job WHERE job_status = 0";
    $getClosedJobsResult = mysqli_query($connection, $getClosedJobsQuery);


    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="adminDashboard.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php include 'adminHeader.html'; ?>


<main>
    <h2>Unverified Jobs</h2>
    <section>
        
        <table class="unverified-jobs-table">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Action</th>
                </tr>
                <tbody>
                    <?php
    if(mysqli_num_rows($getClosedJobsResult) > 0){

        while ($row = mysqli_fetch_assoc($getClosedJobsResult)) {
            $jobId = $row["job_id"];
            $jobTitle = $row["job_title"];

            echo "<tr>";
            echo "<td>";
            echo "<a href='http://localhost/freelancing-website/dashboard/admin/verifyJobDetails.php?job_id=$jobId'>" . $row["job_title"] ."</a>";
             echo "</td>";
         


                          echo "<td class='btn-grp'>
                    <form class='addjobs' action='' method='post'>
                        <input type='hidden' class='verify' name='verify_jobs' value='" . $row['job_id'] . "'>
                        <input type='submit' class='verify' name='verify_jobs'  value='Verify'>
                    </form>
                    <form class='addjobs' action='' method='post'>
                        <input  class='reject'  type='hidden' name='delete_jobs' value='" . $row['job_id'] . "'>
                        <input class='reject' type='submit' name='delete_jobs' value='Delete'>
                    </form>
                </td>";
                          echo "</tr>";
        }

    } else {
        echo "<tr>";
        echo "<td colspan=2>No jobs found</td>";
                echo "</tr>";
    }
                    ?>
                    
                </tbody>
            </thead>
        </table>
    </section>
</main>

</body>
</html>



<?php
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

// Handle skill  
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


