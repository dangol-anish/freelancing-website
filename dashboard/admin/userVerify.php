<?php

include("../../config/database/databaseConfig.php");

session_start(); 

if (isset($_GET["user_verification_id"]) && isset($_SESSION["login"]) && isset($_GET["user_type"])) {

    $userId = $_GET["user_verification_id"];
    $userType = $_GET["user_type"];
    $freelancerBio = "";

    $getUsersQuery = "SELECT user_first_name, user_last_name, user_email,user_phone_number, user_type, user_photo FROM user WHERE user_id = '$userId'";
    
    $getUsersResult = mysqli_query($connection, $getUsersQuery);

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

    if ($userType == "Client") {

        $getClientsQuery = "SELECT * FROM client WHERE user_id = '$userId'";
        $getClientsResult = mysqli_query($connection, $getClientsQuery);

        if (mysqli_num_rows($getClientsResult) > 0) {
            while ($row = mysqli_fetch_assoc($getClientsResult)) {
                $clientPanPhoto = $row["client_pan_photo"];
                $clientVerificationPhoto = $row["client_verification_photo"];
            }
        }
    } elseif ($userType == "Freelancer") {

        $getFreelancersQuery = "SELECT * FROM freelancer WHERE user_id = '$userId'";
        $getFreelancersResult = mysqli_query($connection, $getFreelancersQuery);

        if (mysqli_num_rows($getFreelancersResult) > 0) {
            while ($row = mysqli_fetch_assoc($getFreelancersResult)) {
                $freelancerIdentityPhoto = $row["freelancer_identity_photo"];
                $freelancerVerificationPhoto = $row["freelancer_verification_photo"];
                $freelancerBio = $row["freelancer_bio"];
                $freelancerCV = $row["freelancer_cv"];
            
            }
        }
    } else {
        echo "Invalid User Type";
    }
} else {
     header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="userVerify.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header>
        <img  class="logo" src="../../assets/logo/test.png" alt="">
         <nav>
<ul>
          <li>
            <a
              href="http://localhost/freelancing-website/dashboard/admin/adminDashboard.php"
              >Home</a
            >
          </li>
          <li><a href="http://localhost/freelancing-website/config/helper/addSkills.php">Add Skills</a></li>
        </ul>
        <a
          class="logout"
          href="http://localhost/freelancing-website/dashboard/logout.php"
          >Logout</a
        >
    </header>
   
     <main >
        <div class="card">
        
            <div class="user-info">


                <?php if ($userType == "Client"): ?>
                    

                    <!--  client -->
                 
                  <div class="info-details">
                     
                       <div class="user-photo">
                <img src="../../userAuth/userRegistration/<?php echo $userPhoto; ?>" alt="User Photo">
               
            </div>
                   <div class="info-details-text">
                        <h2>Client Information</h2>
                    <p> <strong>Name: </strong> <?php echo $userFirstName . ' ' . $userLastName; ?></p>
                    <p><strong>Email: </strong><?php echo $userEmail; ?></p>
                    <p><strong>Phone Number: </strong> <?php echo $userPhoneNumber; ?></p>
                    <p><strong>User Type: </strong> <?php echo $userType; ?></p> 

               
                   
                 
                    <p><a href="../../userAuth/userRegistration/clientRegistration/<?php echo $clientPanPhoto; ?>" target="_blank">View Client PAN Photo</a></p>
                    <p><a href="../../userAuth/userRegistration/clientRegistration/<?php echo $clientVerificationPhoto; ?>" target="_blank">View Client Verification Photo</a></p>
                    <div class="action-buttons">
                <form  method="POST">
                    <input  type="hidden" name="user_id" value="<?php echo $userId; ?>">
                    <input class="verify" type="submit" name="verify" value="Verify">
                </form>
                <form method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                    <input class="reject" type="submit" name="reject" value="Reject">
                </form>
            </div>

                  </div>
                </div>
                <?php elseif ($userType == "Freelancer"): ?>


                    <!-- freelancer -->
             
                                 <div class="info-details">
                                     
                                      <div class="user-photo">
                <img src="../../userAuth/userRegistration/<?php echo $userPhoto; ?>" alt="User Photo">
                    
            </div>
            <div class="info-details-text">
                  <h2>Freelancer Information</h2>
                <p><strong>Name: </strong>   <?php echo $userFirstName . ' ' . $userLastName; ?></p>
                    <p><strong>Email: </strong> <?php echo $userEmail; ?></p>
                    <p><strong>Phone Number: </strong> <?php echo $userPhoneNumber; ?></p>
                    <p><strong>User Type: </strong> <?php echo $userType; ?></p>
                    <p class="bio"><strong>Freelancer Bio:</strong> <?php echo $freelancerBio; ?></p>
                      <p><a href="../../userAuth/userRegistration/freelancerRegistration/<?php echo $freelancerIdentityPhoto; ?>" target="_blank">View Identity Photo</a></p>
                   
                        <p><a href="../../userAuth/userRegistration/freelancerRegistration/<?php echo $freelancerVerificationPhoto; ?>" target="_blank">View Verification Photo</a></p>
                   
                    <p><a href="../../userAuth/userRegistration/freelancerRegistration/<?php echo $freelancerCV; ?>" target="_blank">View Cirriculum Vitae</a></p>

                      <div class="action-buttons">
                <form  method="POST">
                    <input  type="hidden" name="user_id" value="<?php echo $userId; ?>">
                    <input class="verify" type="submit" name="verify" value="Verify">
                </form>
                <form method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                    <input class="reject" type="submit" name="reject" value="Reject">
                </form>
            </div>

                    

            </div>

                                 </div>
                                   
                  
                    
                   
                <?php else: ?>
                    <p>Invalid User Type</p>
                <?php endif; ?>
          
        </div>
          </div>

                

    
    </main>
</body>
<style>
 
</style>
</html>


<?php


  if (isset($_POST["verify"])) {
        $userId = $_POST["user_id"];
        $verifyQuery = "UPDATE user SET user_status = 1 WHERE user_id = '$userId'";
        $verifyResult = mysqli_query($connection, $verifyQuery);

        if ($verifyResult) {
            header("Location: http://localhost/freelancing-website/dashboard/admin/adminDashboard.php");
        } else {
            echo "Error verifying user: " . mysqli_error($connection);
        }
    }else if (isset($_POST["reject"])) {
        $userId = $_POST["user_id"];
        $verifyQuery = "UPDATE user SET user_status = 2 WHERE user_id = '$userId'";
        $verifyResult = mysqli_query($connection, $verifyQuery);

        if ($verifyResult) {
             header("Location: http://localhost/freelancing-website/dashboard/admin/adminDashboard.php");
        } else {
            echo "Error rejecting user: " . mysqli_error($connection);
        }
    }

?>

