<?php
include("../config/database/databaseConfig.php");

// Check if the job_id is set
if(isset($_GET["job_id"])) {
    $jobId = $_GET["job_id"];
    echo "Job ID: " . $jobId;

    $getUsersIdQuery = "SELECT freelancer_user_id, client_user_id FROM job_application WHERE job_id='$jobId'";
    $getUsersIdResult = mysqli_query($connection, $getUsersIdQuery);

    // Check if query executed successfully
    if($getUsersIdResult) {
        // Check if any rows are returned
        if(mysqli_num_rows($getUsersIdResult) > 0) {
            $row = mysqli_fetch_assoc($getUsersIdResult);
            // Check if client_user_id exists in the result
            if(isset($row["client_user_id"])) {
                $clientUserId = $row["client_user_id"];
                $freelancerUserId = $row["freelancer_user_id"];

              
            } else {
                echo "Client user id or Freelancer user id not found.";
            }
        } else {
            echo "No rows returned.";
        }
    } else {
        echo "Query execution failed: " . mysqli_error($connection);
    }
} else {
    echo "Job ID not provided.";
}


echo $clientUserId;
echo $freelancerUserId;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Chat box </h4>
            </div>
            <div class="modal-body">
               




            <div class="container-fluid">

            <div class="row">
                <div class="col-md-4">
 
                </div>
                  <div class="col-md-4">

                </div>
                  <div class="col-md-4">

                </div>
            </div>
            </div>














    















                

            </div>
        </div>

    </div>
</body>
</html>