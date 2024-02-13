    <!-- updateProfile.php -->
    <link rel="stylesheet" href="freelancer.css">

    <?php
    include("../../config/database/databaseConfig.php");

    $userFirstName = "";
$userLastName = "";
$freelancerBio = "";
$errors = array();

// Initialize other variables to avoid warnings
$identityFolder = "";
$verificationFolder = "";
$cvFolder = "";


    if (!isset($_SESSION["user_id"]) || !isset($_SESSION["login"])) {
        header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
        exit(); 
    }

    if ($_SESSION["user_type"] != "Freelancer") {
        $_SESSION = [];
        session_destroy();
        header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
        exit();
    }

    $userId = $_SESSION["user_id"];

    ?>

    <!-- Modal for updating profile -->
    <div id="updateProfileModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Update Profile</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="userFirstName" value="<?php echo $userFirstName; ?>"><br>
                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="userLastName" value="<?php echo $userLastName; ?>"><br>
                <label for="freelancerBio">Bio:</label>
                <input type="text" id="freelancerBio" name="freelancer_bio" value="<?php echo $freelancerBio; ?>"><br>

             
        <label class="user-photo" for="user_photo">
        <p>Photo URL:</p>
        <input type="file" id="user_photo" name="picture" required />
        </label>
        <br /><br />
                <label for="freelancer_identity_photo">Identity Photo:</label><br />
        <input type="file" id="freelancer_identity_photo" name="freelancer_identity_photo" required /><br /><br />

        <label for="freelancer_verification_photo">Verification Photo:</label><br />
        <input type="file" id="freelancer_verification_photo" name="freelancer_verification_photo" required /><br /><br />

        <label for="freelancer_cv">Verification CV:</label><br />
        <input type="file" id="freelancer_cv" name="freelancer_cv" required /><br /><br />


                <label for="currentPassword">Current Password:</label>
                <input type="password" id="currentPassword" name="userPassword" value="" required><br>
                <label for="newPassword">New Password:</label>
                <input type="password" id="newPassword" name="newPassword" value="" required><br>
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" value="" required><br>
                
                <input type="submit" value="Update Profile" name="update">
            </form>
        </div>
    </div>

    <?php

    // Check if the form has been submitted
    if(isset($_POST["update"])) {
        // Retrieve form data
        $userFirstName = $_POST["userFirstName"];
        $userLastName = $_POST["userLastName"];
      
        $currentPassword = $_POST["userPassword"];
        $newPassword = $_POST["newPassword"];
        $confirmPassword = $_POST["confirmPassword"];

        // Validate and process the file upload for user photo
        
    
         $fileName = $_FILES['picture']['name'];  
            $tempName = $_FILES['picture']['tmp_name'];
           $folder = "pics/"; // Base folder path
$filePath = $folder . $fileName; // Concatenate folder path with filename


            if (move_uploaded_file($tempName, $filePath)) {
    
            } else {
                $errors[] = "Error moving file.";
            }

            if(isset($_FILES['freelancer_identity_photo'])) {
                        $freelancerIdentityPhoto = $_FILES['freelancer_identity_photo']['name'];
                        $photoTempName = $_FILES['freelancer_identity_photo']['tmp_name'];
                        $identityFolder = "identity/".$freelancerIdentityPhoto;

                        if (move_uploaded_file($photoTempName, $identityFolder)) {
                   
                        } else {
                            $errors[] = "Error moving identity photo file.";
                        }
                    } else {
                        $errors[] = "Identity photo file not provided.";
                    }

                    // Check if 'freelancer_verification_photo' index is set
                    if(isset($_FILES['freelancer_verification_photo'])) {
                        $freelancerVerificationPhoto = $_FILES['freelancer_verification_photo']['name'];
                        $photoTempName = $_FILES['freelancer_verification_photo']['tmp_name'];
                        $verificationFolder = "verification/".$freelancerVerificationPhoto;

                        if (move_uploaded_file($photoTempName, $verificationFolder)) {
                  
                        } else {
                            $errors[] = "Error moving verification photo file.";
                        }
                    } else {
                        $errors[] = "Verification photo file not provided.";
                    }

                   $freelancerBio = isset($_POST["freelancer_bio"]) ? $_POST["freelancer_bio"] : "";


                    // Check if 'freelancer_cv' index is set
                    if(isset($_FILES['freelancer_cv'])) {
                        $freelancerCv = $_FILES['freelancer_cv']['name'];
                        $freelancerTempName = $_FILES['freelancer_cv']['tmp_name'];
                        $cvFolder = "cv/".$freelancerCv;

                        if (move_uploaded_file($freelancerTempName, $cvFolder)) {
     
                        } else {
                            $errors[] = "Error moving CV file.";
                        }
                    } else {
                        $errors[] = "CV file not provided.";
                    }

    

        $getUserPasswordQuery = "SELECT user_password FROM user WHERE user_id='$userId'";
        $getUserPasswordResult = mysqli_query($connection, $getUserPasswordQuery);

        if(mysqli_num_rows($getUserPasswordResult) > 0) {
            $row = mysqli_fetch_assoc($getUserPasswordResult);
            $storedUserPassword = $row["user_password"];
            if (password_verify($currentPassword, $storedUserPassword)) {
                $updateProfileQuery = "UPDATE user SET user_first_name='$userFirstName', user_last_name='$userLastName', user_photo='$filePath' WHERE user_id='$userId'";
                $updateProfileResult = mysqli_query($connection, $updateProfileQuery);

                  $updateFreelancerQuery = "UPDATE freelancer SET freelancer_identity_photo='$identityFolder', freelancer_verification_photo='$verificationFolder', freelancer_bio='$freelancerBio', freelancer_cv = '$cvFolder' WHERE user_id='$userId'";
                $updateFreelancerResult = mysqli_query($connection, $updateFreelancerQuery);
                if ($updateProfileResult && $updateFreelancerResult) {
                    // Profile updated successfully
                    echo "Profile updated successfully!";
                } else {
                    // Error updating profile
                    echo "Error updating profile.";
                }
            }
        }


    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
    ?>
