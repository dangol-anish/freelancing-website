<?php

include("../../config/database/databaseConfig.php");

session_start(); 

if (isset($_GET["freelancer_user_id"]) && isset($_SESSION["login"]) && isset($_GET["user_type"]) && isset($_GET["client_user_id"])) {


    $freelancerUserId = $_GET["freelancer_user_id"];
    $userType = $_GET["user_type"];
    $clientUserId = $_GET["client_user_id"];
    $jobId = $_GET["job_id"];




    // echo "Freelancer User Id: " . $freelancerUserId;
    // echo "Client User Id: " . $clientUserId;
    // echo "Job Id: " . $jobId;

    $getUsersQuery = "SELECT * FROM user WHERE user_id = '$freelancerUserId'";
    
    $getUsersResult = mysqli_query($connection, $getUsersQuery);


    $checkJAStatusQuery = "select ja_status from job_application where client_user_id='$clientUserId' and freelancer_user_id='$freelancerUserId' and job_id='$jobId'";


    $checkJAStatusResult = mysqli_query($connection, $checkJAStatusQuery);

    $row = mysqli_fetch_assoc($checkJAStatusResult);

    $jobStatus = $row["ja_status"];

    echo $jobStatus;
    if($jobStatus == 1){
        header("Location: http://localhost/freelancing-website/communication/chatbox.php?job_id=$jobId&toUser=$freelancerUserId");
    }else if($jobStatus == 2){
        header("Location: http://localhost/freelancing-website/dashboard/client/clientDashboard.php");

    }











    if (mysqli_num_rows($getUsersResult) > 0) {
        while ($row = mysqli_fetch_assoc($getUsersResult)) {
            $userFirstName = $row['user_first_name'];
            $userLastName = $row['user_last_name'];
            $userEmail = $row['user_email'];
            $userPhoneNumber = $row['user_phone_number'];
            $userType = $row['user_type'];
            $userPhoto = $row['user_photo'];
        }
    }



        $getFreelancersQuery = "SELECT * FROM freelancer WHERE user_id = '$freelancerUserId'";
        $getFreelancersResult = mysqli_query($connection, $getFreelancersQuery);

        if (mysqli_num_rows($getFreelancersResult) > 0) {
            while ($row = mysqli_fetch_assoc($getFreelancersResult)) {
                $freelancerIdentityPhoto = $row["freelancer_identity_photo"];
                $freelancerVerificationPhoto = $row["freelancer_verification_photo"];
                $freelancerBio = $row["freelancer_bio"];
                $freelancerCV = $row["freelancer_cv"];
            
            }
        }
    }else{
         // Redirect the user if not logged in or not a Freelancer
    $_SESSION = [];
    session_destroy();
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
    exit; // Make sure to exit after header redirection

    }

    
   
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="adminDashboard.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <nav>
    <ul>
        
   
    </ul>

</nav>
     <main>
        <div class="card">
            <div class="user-photo">
                <img src="../../userAuth/userRegistration/<?php echo $userPhoto; ?>" alt="User Photo">
            </div>
            <div class="user-info">
                <?php if ($userType == "Client"): ?>
                    <h2>Client Information</h2>
                    <p><strong>Name:</strong> <?php echo $userFirstName . ' ' . $userLastName; ?></p>
                    <p><strong>Email:</strong> <?php echo $userEmail; ?></p>
                    <p><strong>Phone Number:</strong> <?php echo $userPhoneNumber; ?></p>
                    <p><strong>User Type:</strong> <?php echo $userType; ?></p>
                    <!-- Client images as clickable links -->
                    <p><a href="../../userAuth/userRegistration/clientRegistration/<?php echo $clientPanPhoto; ?>" target="_blank">View Client PAN Photo</a></p>
                    <p><a href="../../userAuth/userRegistration/clientRegistration/<?php echo $clientVerificationPhoto; ?>" target="_blank">View Client Verification Photo</a></p>
                <?php elseif ($userType == "Freelancer"): ?>
                    <h2>Freelancer Information</h2>
                    <p><strong>Name:</strong> <?php echo $userFirstName . ' ' . $userLastName; ?></p>
                    <p><strong>Email:</strong> <?php echo $userEmail; ?></p>
                    <p><strong>Phone Number:</strong> <?php echo $userPhoneNumber; ?></p>
                    <p><strong>User Type:</strong> <?php echo $userType; ?></p>
                    <p><strong>Freelancer Bio:</strong> <?php echo $freelancerBio; ?></p>
                      <p><a href="../../userAuth/userRegistration/freelancerRegistration/<?php echo $freelancerIdentityPhoto; ?>" target="_blank">View Freelancer Identity Photo</a></p>
                   
                        <p><a href="../../userAuth/userRegistration/freelancerRegistration/<?php echo $freelancerVerificationPhoto; ?>" target="_blank">View Freelancer Verification Photo</a></p>
                   
                    <p><a href="../../userAuth/userRegistration/freelancerRegistration/<?php echo $freelancerCV; ?>" target="_blank">View CV</a></p>
                   
                <?php else: ?>
                    <p>Invalid User Type</p>
                <?php endif; ?>
          
        </div>
          </div>

                 <div class="action-buttons">
                <form  method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $freelancerUserId; ?>">
                    <input type="submit" name="hire" value="Hire">
                </form>
                <form method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $freelancerUserId; ?>">
                    <input type="submit" name="reject" value="Reject">
                </form>
            </div>

                    
    </main>
</body>
<style>
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
    }
    main {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        height: 100%;
       
    }
    .card {
         background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 20px;
        max-width: 600px;
        display: flex;
    }
    .user-photo {
        margin-right: 20px;
        max-width: 200px;
        height: auto;
    }
    .user-info {
        flex: 1;
    }
    .user-info h2 {
        margin-top: 0;
    }
    .user-info p {
        margin-bottom: 10px;
    }
    .user-info a {
        display: block;
        margin-top: 10px;
    }
    .user-photo img {
        max-width: 200px;
        max-height: 200px;
        width: auto;
        height: auto;
    }
</style>
</html>


<?php


  if (isset($_POST["hire"])) {
   
        $hireQuery = "UPDATE job_application SET ja_status = 1 WHERE freelancer_user_id = '$freelancerUserId' AND client_user_id = '$clientUserId' AND job_id = '$jobId'";
        $hireResult = mysqli_query($connection, $hireQuery);





         // echo "Freelancer User Id: " . $freelancerUserId;
    // echo "Client User Id: " . $clientUserId;
    // echo "Job Id: " . $jobId;


        if ($hireResult) {
           header("Location: http://localhost/freelancing-website/communication/chatbox.php?job_id=$jobId");
        } else {
            echo "Error hiring user: " . mysqli_error($connection);
        }
    }else if (isset($_POST["reject"])) {
   
        $rejectQuery = "UPDATE job_application SET ja_status = 2 WHERE freelancer_user_id = '$freelancerUserId' AND client_user_id = '$clientUserId' AND job_id = '$jobId'";
        $rejectResult = mysqli_query($connection, $rejectQuery);



        if ($rejectResult) {

            header("Location: http://localhost/freelancing-website/dashboard/client/clientDashboard.php");
        } else {
            echo "Error rejecting user: " . mysqli_error($connection);
        }

     

    }

?>