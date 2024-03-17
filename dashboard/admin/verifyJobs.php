
<?php
session_start(); // Start the session if not already started

if (isset($_SESSION["user_id"]) && isset($_SESSION["login"])) {

    if ($_SESSION["user_type"] != "Admin") {
        $_SESSION = [];
        session_destroy();
        header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
        exit();
    }
} else {
    $_SESSION = [];
    session_destroy();
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
    exit();
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
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($getClosedJobsResult) > 0) {

                        while ($row = mysqli_fetch_assoc($getClosedJobsResult)) {
                            $jobId = $row["job_id"];
                            $jobTitle = $row["job_title"];

                            echo "<tr>";
                            echo "<td>";
                            echo "<a href='http://localhost/freelancing-website/dashboard/admin/verifyJobDetails.php?job_id=$jobId'>" . $row["job_title"] . "</a>";
                            echo "</td>";
                            echo "<td class='btn-grp'>
                                    <form class='verifyForm' action='' method='post'>
                                        <input type='hidden' name='verify_job_id' value='" . $row['job_id'] . "'>
                                        <input type='submit' name='verify_job' value='Verify'>
                                    </form>
                                    <form class='deleteJobForm' action='' method='post'>
                                        <input type='hidden' name='delete_job_id' value='" . $row['job_id'] . "'>
                                        <input type='submit' name='delete_job' value='Delete'>
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
            </table>
        </section>
    </main>

</body>

</html>

<?php
// Handle job verification
if (isset($_POST['verify_job'])) {
    // Check if the job ID is set and numeric
    if (isset($_POST['verify_job_id']) && is_numeric($_POST['verify_job_id'])) {
        $jobIdToVerify = $_POST['verify_job_id'];

        // Perform verification query
        $verifyQuery = "UPDATE job SET job_status = 1 WHERE job_id = $jobIdToVerify";
        $verifyResult = mysqli_query($connection, $verifyQuery);

        if ($verifyResult) {
            // Redirect to the same page after verification
            header("Location: http://localhost/freelancing-website/dashboard/admin/verifyJobs.php");
            exit(); // Make sure to exit after redirection
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    } else {
        echo "Invalid job ID";
    }
}

// Handle job deletion
if (isset($_POST['delete_job'])) {
    // Check if the job ID is set and numeric
    if (isset($_POST['delete_job_id']) && is_numeric($_POST['delete_job_id'])) {
        $jobIdToDelete = $_POST['delete_job_id'];

        // Perform deletion query
        $deleteQuery = "DELETE FROM job WHERE job_id = $jobIdToDelete";
        $deleteResult = mysqli_query($connection, $deleteQuery);

        if ($deleteResult) {
            // Redirect to the same page after deletion
            header("Location: http://localhost/freelancing-website/dashboard/admin/verifyJobs.php");
            exit(); // Make sure to exit after redirection
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    } else {
        echo "Invalid job ID";
    }
}
?>
